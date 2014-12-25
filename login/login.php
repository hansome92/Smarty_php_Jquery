<?php
    require_once 'mongodatabase.php';

    class login extends mongodatabase {
        function __construct( $userData, $method ) {
            parent::__construct();
            $user = $this->makeUserFromUserData( $userData, $method );
            $user['type'] = 'login';

            $user['id'] = $this->getExistingOrRegisterNewUser( $user );

            $user['loggedIn'] = TRUE;
            $this->userToSession( $user );
        }

        function makeUserFromUserData( $userData, $method ) {
            if( $method == 'facebook' ) {
                $userData['authSource'] = 'facebook';
                $user = $this->facebookLogin( $userData );
            } elseif( $method == 'oid' ) {
                $userData['authSource'] = $_SESSION['authSource'];
                $user = $this->openidLogin( $userData );
            } else {
                die( 'LOGIN METHOD WAS NOT OPENID OR FACEBOOK' );
            }
            if( !isset( $user['logoutUrl'] ) ) {
                $user['logoutUrl'] = LOGOUT_URL;
            }

            return $user;
        }

        function facebookLogin( $userData ) {
            $user = Array();
            $user['email'] = (string)$userData['email'];
            $user['username'] = (string)$userData['username'];
            $user['fname'] = (string)$userData['first_name'];
            $user['lname'] = (string)$userData['last_name'];
            $user['name'] = $user['fname'].' '.$user['lname'];

            $user['fbUid'] = (string)$userData['id'];
            $user['timezone'] = (string)$userData['timezone'];
            $user['authSource'] = (string)$userData['authSource'];

            return $user;
            //  fields: "id", "name", "first_name", "last_name", "link", "username", "gender", "email", "timezone", "locale", "verified", "updated_time"
        }

        function openidLogin( $userData ) {
            $user = Array();
            $user['email'] = (string)$userData["contact/email"];

            if( isset( $userData["namePerson/first"] ) ) {
                $user['fname'] = (string)$userData["namePerson/first"];
            }
            if( isset( $userData["namePerson/last"] ) ) {
                $user['lname'] = (string)$userData["namePerson/last"];
            }

            //set name/nameperson/friendly/etc vars
            if( isset( $userData["namePerson/friendly"] ) && isset( $userData["namePerson"] ) ) {
                $user['name'] = (string)$userData["namePerson/friendly"];
                $user['username'] = (string)$userData["namePerson"];
            } elseif( isset( $userData["namePerson/friendly"] ) xor isset( $userData["namePerson"] ) ) {
                if( isset( $userData["namePerson/friendly"] ) ) {
                    $user['name'] = (string)$userData["namePerson/friendly"];
                } else {
                    $user['username'] = (string)$userData["namePerson"];
                }
            } else {
                if( isset( $userData["namePerson/first"] ) && isset( $userData["namePerson/last"] ) ) {
                    $user['name'] = $user['fname'].' '.$user['lname'];
                }
            }

            $user['authSource'] = (string)$userData['authSource'];
            return $user;
        }

        //returns the MongoID of the existing or new user
        function getExistingOrRegisterNewUser( $user ) {
            $usersCollection = $this->db->selectCollection( "Users" );

            $foundUser = $usersCollection->findOne( Array( 'authentications.login.email'      => $user['email'],
                                                           'authentications.login.authSource' => $user['authSource']
                                                    ) );

            if( sizeof( $foundUser ) > 1 ) {
                $userId = $foundUser['_id']->{'$id'};
            } else {
                //add fields for creating a new user
                $user['contactEmail'] = $user['email'];
                $user['displayName'] = $user['name'];
                $user['description'] = "";
                $user['regDate'] = new MongoDate();
                $newUser = Array( 'authentications' => Array( 'login' => $user,
                    /*'content' => Array()*/
                ),
                                  'scrapbooks'      => Array(),
                                  'paidUser'        => FALSE,
                );
                $usersCollection->insert( $newUser );
                $userId = $newUser['_id'];

                require_once 'mysqlDatabase.php';
                $mysql = new mysqlDatabase();
                $userid = $mysql->escapeString( $userId );
                $contactEmail = $mysql->escapeString( $user['email'] );
                $displayName = $mysql->escapeString(  $user['name'] );
                $username = $mysql->escapeString( $user['username'] );
                $fname = $mysql->escapeString( $user['fname'] );
                $lname = $mysql->escapeString( $user['lname'] );
                $name = $mysql->escapeString( $user['name'] );
                $fbUid = ( isset( $user['fbUid'] ) ? $mysql->escapeString( $user['fbUid'] ) : '' );
                $authSource = $mysql->escapeString( $user['authSource'] );
                $timezone = ( isset( $user['timezone'] ) ? $mysql->escapeString( $user['timezone'] ) : '' );
                $description = '';
                $quer = "INSERT INTO users(userid, contactEmail, displayName, username, fname, lname, name, fbUid, authSource, timezone, description) VALUES('{$userid}', '{$contactEmail}', '{$displayName}', '{$username}', '{$fname}', '{$lname}', '{$name}', '{$fbUid}', '{$authSource}', '{$timezone}', '{$description}')";
                $mysql->query( $quer );
            }

            return $userId;
            //$login = array( '$addToSet'=> array( 'authentications'=> $user ) );
        }

        function userToSession( $user ) {
            foreach( $user as $field => $value ) {
                $_SESSION[$field] = $value;
            }
        }
    }
