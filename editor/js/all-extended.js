/**
 * Dav Start
 * FabricJS extensions
 **/
(function() {
    /**
     * @namespace
     */
    fabric.imgutil = { };

    /**
     * Removes value from an array.
     * Presence of value (and its position in an array) is determined via `Array.prototype.indexOf`
     * @static
     * @memberOf fabric.util
     * @method removeFromArray
     * @param {Array} array
     * @param {Any} value
     * @return {Array} original array
     */

    /** contrast **/
    function contrast( adjust ) {
        adjust = Math.pow( (adjust + 100) / 100, 2 );
        var process = function( rgba ) {
            rgba.r /= 255;
            rgba.r -= 0.5;
            rgba.r *= adjust;
            rgba.r += 0.5;
            rgba.r *= 255;
            rgba.g /= 255;
            rgba.g -= 0.5;
            rgba.g *= adjust;
            rgba.g += 0.5;
            rgba.g *= 255;
            rgba.b /= 255;
            rgba.b -= 0.5;
            rgba.b *= adjust;
            rgba.b += 0.5;
            rgba.b *= 255;
            return rgba;
        }

        return process;
    }

    /** noise **/
    function noise( adjust ) {
        adjust = Math.abs( adjust ) * 2.55;
        var process = function( rgba ) {
            var rand;
            rand = Calculate.randomRange( adjust * -1, adjust );
            rgba.r += rand;
            rgba.g += rand;
            rgba.b += rand;
            return rgba;
        }
        return process;
    }

    function sepia( adjust ) {
        if( adjust == null ) {
            adjust = 100;
        }

        adjust /= 100;
        var process = function( rgba ) {
            rgba.r = Math.min( 255, (rgba.r * (1 - (0.607 * adjust))) + (rgba.g * (0.769 * adjust)) + (rgba.b * (0.189 * adjust)) );
            rgba.g = Math.min( 255, (rgba.r * (0.349 * adjust)) + (rgba.g * (1 - (0.314 * adjust))) + (rgba.b * (0.168 * adjust)) );
            rgba.b = Math.min( 255, (rgba.r * (0.272 * adjust)) + (rgba.g * (0.534 * adjust)) + (rgba.b * (1 - (0.869 * adjust))) );
            return rgba;
        }
        return process;
    }

    function channels( options ) {
        var chan, value;
        if( typeof options !== "object" ) {
            return this;
        }

        for( chan in options ) {

            value = options[chan];
            if( value === 0 ) {
                delete options[chan];
                continue;
            }
            options[chan] /= 100;
        }
        if( options.length === 0 ) {
            return this;
        }

        var process = function( rgba ) {
            if( options.red != null ) {
                if( options.red > 0 ) {
                    rgba.r += (255 - rgba.r) * options.red;
                } else {
                    rgba.r -= rgba.r * Math.abs( options.red );
                }
            }
            if( options.green != null ) {
                if( options.green > 0 ) {
                    rgba.g += (255 - rgba.g) * options.green;
                } else {
                    rgba.g -= rgba.g * Math.abs( options.green );
                }
            }
            if( options.blue != null ) {
                if( options.blue > 0 ) {
                    rgba.b += (255 - rgba.b) * options.blue;
                } else {
                    rgba.b -= rgba.b * Math.abs( options.blue );
                }
            }
            return rgba;
        }
        return process;
    }

    function gamma( adjust ) {
        var process = function( rgba ) {
            rgba.r = Math.pow( rgba.r / 255, adjust ) * 255;
            rgba.g = Math.pow( rgba.g / 255, adjust ) * 255;
            rgba.b = Math.pow( rgba.b / 255, adjust ) * 255;
            return rgba;
        }
        return process;
    }

    function greyscale() {
        var process = function( rgba ) {

            var avg;
            avg = 0.3 * rgba.r + 0.59 * rgba.g + 0.11 * rgba.b;
            rgba.r = avg;
            rgba.g = avg;
            rgba.b = avg;
            return rgba;
        }
        return process;
    }

    function locationXY( width, height, loc ) {
        var x, y;
        y = height - Math.floor( loc / (width * 4) );
        x = (loc % (width * 4)) / 4;
        return {
            x: x,
            y: y
        };
    }

    function vignette( size, strength, data, width, height ) {
        var bezier, center, end, start;
        if( strength == null ) {
            strength = 60;
        }
        if( typeof size === "string" && size.substr( -1 ) === "%" ) {
            if( height > width ) {
                size = width * (parseInt( size.substr( 0, size.length - 1 ), 10 ) / 100);
            } else {
                size = height * (parseInt( size.substr( 0, size.length - 1 ), 10 ) / 100);
            }
        }
        strength /= 100;

        center = [width / 2, height / 2];
        start = Math.sqrt( Math.pow( center[0], 2 ) + Math.pow( center[1], 2 ) );
        end = start - size;
        bezier = Calculate.bezier( [0, 1], [30, 30], [70, 60], [100, 80] );

        var r, g, b;
        for( var i = 0, len = data.length; i < len; i += 4 ) {
            var dist, div, loc;
            loc = locationXY( width, height, i );

            dist = Calculate.distance( loc.x, loc.y, center[0], center[1] );

            r = data[i];
            g = data[i + 1];
            b = data[i + 2];
            if( dist > end ) {

                div = Math.max( 1, (bezier[Math.round( ((dist - end) / size) * 100 )] / 10) * strength );
                r = Math.pow( r / 255, div ) * 255;
                g = Math.pow( g / 255, div ) * 255;
                b = Math.pow( b / 255, div ) * 255;
            }
            data[i] = r;
            data[i + 1] = g;
            data[i + 2] = b;

        }

        return data;
    }

    function curves( chans, start, ctrl1, ctrl2, end ) {
        var bezier, i, _ref, _ref2;
        if( typeof chans === "string" ) {
            chans = chans.split( "" );
        }
        bezier = Calculate.bezier( start, ctrl1, ctrl2, end, 0, 255 );
        if( start[0] > 0 ) {
            for( i = 0, _ref = start[0]; 0 <= _ref ? i < _ref : i > _ref; 0 <= _ref ? i++ : i-- ) {
                bezier[i] = start[1];
            }
        }
        if( end[0] < 255 ) {
            for( i = _ref2 = end[0]; _ref2 <= 255 ? i <= 255 : i >= 255; _ref2 <= 255 ? i++ : i-- ) {
                bezier[i] = end[1];
            }
        }

        var process = function( rgba ) {

            var i, _ref3;
            for( i = 0, _ref3 = chans.length; 0 <= _ref3 ? i < _ref3 : i > _ref3; 0 <= _ref3 ? i++ : i-- ) {
                rgba[chans[i]] = bezier[rgba[chans[i]]];
            }
            return rgba;
        };

        return process;

    }

    function exposure( adjust ) {
        var ctrl1, ctrl2, p;
        p = Math.abs( adjust ) / 100;
        ctrl1 = [0, 255 * p];
        ctrl2 = [255 - (255 * p), 255];
        if( adjust < 0 ) {
            ctrl1 = ctrl1.reverse();
            ctrl2 = ctrl2.reverse();
        }

        return curves( 'rgb', [0, 0], ctrl1, ctrl2, [255, 255] );
    }

    function saturation( adjust ) {
        adjust *= -0.01;
        var process = function( rgba ) {
            var max;
            max = Math.max( rgba.r, rgba.g, rgba.b );
            if( rgba.r !== max ) {
                rgba.r += (max - rgba.r) * adjust;
            }
            if( rgba.g !== max ) {
                rgba.g += (max - rgba.g) * adjust;
            }
            if( rgba.b !== max ) {
                rgba.b += (max - rgba.b) * adjust;
            }
            return rgba;
        }

        return process;

    }

    function vibrance( adjust ) {
        adjust *= -1;
        var process = function( rgba ) {
            var amt, avg, max;
            max = Math.max( rgba.r, rgba.g, rgba.b );
            avg = (rgba.r + rgba.g + rgba.b) / 3;
            amt = ((Math.abs( max - avg ) * 2 / 255) * adjust) / 100;
            if( rgba.r !== max ) {
                rgba.r += (max - rgba.r) * amt;
            }
            if( rgba.g !== max ) {
                rgba.g += (max - rgba.g) * amt;
            }
            if( rgba.b !== max ) {
                rgba.b += (max - rgba.b) * amt;
            }
            return rgba;
        }

        return process;
    }

    function colorize() {
        var level, rgb;
        if( arguments.length === 2 ) {
            rgb = Convert.hexToRGB( arguments[0] );
            level = arguments[1];
        } else if( arguments.length === 4 ) {
            rgb = {
                r: arguments[0],
                g: arguments[1],
                b: arguments[2]
            };
            level = arguments[3];
        }
        var process = function( rgba ) {
            rgba.r -= (rgba.r - rgb.r) * (level / 100);
            rgba.g -= (rgba.g - rgb.g) * (level / 100);
            rgba.b -= (rgba.b - rgb.b) * (level / 100);
            return rgba;
        }
        return process;
    };

    function brightness( adjust ) {
        adjust = Math.floor( 255 * (adjust / 100) );
        var process = function( rgba ) {
            rgba.r += adjust;
            rgba.g += adjust;
            rgba.b += adjust;
            return rgba;
        };
        return process;
    }

    function posterize( adjust ) {
        var numOfAreas, numOfValues;
        numOfAreas = 256 / adjust;
        numOfValues = 255 / (adjust - 1);
        var process = function( rgba ) {
            rgba.r = Math.floor( Math.floor( rgba.r / numOfAreas ) * numOfValues );
            rgba.g = Math.floor( Math.floor( rgba.g / numOfAreas ) * numOfValues );
            rgba.b = Math.floor( Math.floor( rgba.b / numOfAreas ) * numOfValues );
            return rgba;
        };

        return process;
    }

    function clip( adjust ) {
        adjust = Math.abs( adjust ) * 2.55;
        var process = function( rgba ) {
            if( rgba.r > 255 - adjust ) {
                rgba.r = 255;
            } else if( rgba.r < adjust ) {
                rgba.r = 0;
            }
            if( rgba.g > 255 - adjust ) {
                rgba.g = 255;
            } else if( rgba.g < adjust ) {
                rgba.g = 0;
            }
            if( rgba.b > 255 - adjust ) {
                rgba.b = 255;
            } else if( rgba.b < adjust ) {
                rgba.b = 0;
            }
            return rgba;
        }
        return process;
    }

    function fillColor() {
        var color;
        if( arguments.length === 1 ) {
            color = Convert.hexToRGB( arguments[0] );
        } else {
            color = {
                r: arguments[0],
                g: arguments[1],
                b: arguments[2]
            };
        }
        var process = function( rgba ) {
            rgba.r = color.r;
            rgba.g = color.g;
            rgba.b = color.b;
            rgba.a = 255;
            return rgba;
        };

        return process;
    }

    fabric.imgutil.opacity = function( adjust ) {
        adjust = adjust / 100;

        var process = function( rgba ) {
            rgba.a = 255 * adjust;
            return rgba;
        };
        return process;
    }

    BlurStack = function() {
        this.r = 0;
        this.g = 0;
        this.b = 0;
        this.a = 0;
        return this.next = null;
    };

    mul_table =
    [512, 512, 456, 512, 328, 456, 335, 512, 405, 328, 271, 456, 388, 335, 292, 512, 454, 405, 364, 328, 298, 271, 496, 456, 420, 388, 360, 335, 312, 292, 273, 512, 482, 454, 428, 405, 383, 364, 345,
     328, 312, 298, 284, 271, 259, 496, 475, 456, 437, 420, 404, 388, 374, 360, 347, 335, 323, 312, 302, 292, 282, 273, 265, 512, 497, 482, 468, 454, 441, 428, 417, 405, 394, 383, 373, 364, 354, 345,
     337, 328, 320, 312, 305, 298, 291, 284, 278, 271, 265, 259, 507, 496, 485, 475, 465, 456, 446, 437, 428, 420, 412, 404, 396, 388, 381, 374, 367, 360, 354, 347, 341, 335, 329, 323, 318, 312, 307,
     302, 297, 292, 287, 282, 278, 273, 269, 265, 261, 512, 505, 497, 489, 482, 475, 468, 461, 454, 447, 441, 435, 428, 422, 417, 411, 405, 399, 394, 389, 383, 378, 373, 368, 364, 359, 354, 350, 345,
     341, 337, 332, 328, 324, 320, 316, 312, 309, 305, 301, 298, 294, 291, 287, 284, 281, 278, 274, 271, 268, 265, 262, 259, 257, 507, 501, 496, 491, 485, 480, 475, 470, 465, 460, 456, 451, 446, 442,
     437, 433, 428, 424, 420, 416, 412, 408, 404, 400, 396, 392, 388, 385, 381, 377, 374, 370, 367, 363, 360, 357, 354, 350, 347, 344, 341, 338, 335, 332, 329, 326, 323, 320, 318, 315, 312, 310, 307,
     304, 302, 299, 297, 294, 292, 289, 287, 285, 282, 280, 278, 275, 273, 271, 269, 267, 265, 263, 261, 259];
    shg_table =
    [9, 11, 12, 13, 13, 14, 14, 15, 15, 15, 15, 16, 16, 16, 16, 17, 17, 17, 17, 17, 17, 17, 18, 18, 18, 18, 18, 18, 18, 18, 18, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 20, 20, 20, 20,
     20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 22, 22, 22, 22, 22, 22, 22, 22,
     22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23,
     23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24,
     24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24,
     24, 24, 24, 24, 24, 24, 24, 24, 24, 24];

    function stackBlur( pixels, width, height, radius ) {
        var b_in_sum, b_out_sum, b_sum, div, g_in_sum, g_out_sum, g_sum, heightMinus1, i, mul_sum, p, pb, pg, pr, r_in_sum, r_out_sum, r_sum, radiusPlus1, rbs, shg_sum, stack, stackEnd, stackIn, stackOut, stackStart, sumFactor, w4, widthMinus1, x, y, yi, yp, yw;
        if( isNaN( radius ) || radius < 1 ) {
            return;
        }
        radius |= 0;
        div = radius + radius + 1;
        w4 = width << 2;
        widthMinus1 = width - 1;
        heightMinus1 = height - 1;
        radiusPlus1 = radius + 1;
        sumFactor = radiusPlus1 * (radiusPlus1 + 1) / 2;
        stackStart = new BlurStack();
        stack = stackStart;
        for( i = 1; 1 <= div ? i < div : i > div; 1 <= div ? i++ : i-- ) {
            stack = stack.next = new BlurStack();
            if( i === radiusPlus1 ) {
                stackEnd = stack;
            }
        }

        stack.next = stackStart;
        stackIn = null;
        stackOut = null;
        yw = yi = 0;
        mul_sum = mul_table[radius];
        shg_sum = shg_table[radius];
        for( y = 0; 0 <= height ? y < height : y > height; 0 <= height ? y++ : y-- ) {
            r_in_sum = g_in_sum = b_in_sum = r_sum = g_sum = b_sum = 0;
            r_out_sum = radiusPlus1 * (pr = pixels[yi]);
            g_out_sum = radiusPlus1 * (pg = pixels[yi + 1]);
            b_out_sum = radiusPlus1 * (pb = pixels[yi + 2]);
            r_sum += sumFactor * pr;
            g_sum += sumFactor * pg;
            b_sum += sumFactor * pb;
            stack = stackStart;
            for( i = 0; 0 <= radiusPlus1 ? i < radiusPlus1 : i > radiusPlus1; 0 <= radiusPlus1 ? i++ : i-- ) {
                stack.r = pr;
                stack.g = pg;
                stack.b = pb;
                stack = stack.next;
            }
            for( i = 1; 1 <= radiusPlus1 ? i < radiusPlus1 : i > radiusPlus1; 1 <= radiusPlus1 ? i++ : i-- ) {
                p = yi + ((widthMinus1 < i ? widthMinus1 : i) << 2);
                r_sum += (stack.r = (pr = pixels[p])) * (rbs = radiusPlus1 - i);
                g_sum += (stack.g = (pg = pixels[p + 1])) * rbs;
                b_sum += (stack.b = (pb = pixels[p + 2])) * rbs;
                r_in_sum += pr;
                g_in_sum += pg;
                b_in_sum += pb;
                stack = stack.next;
            }
            stackIn = stackStart;
            stackOut = stackEnd;
            for( x = 0; 0 <= width ? x < width : x > width; 0 <= width ? x++ : x-- ) {
                pixels[yi] = (r_sum * mul_sum) >> shg_sum;
                pixels[yi + 1] = (g_sum * mul_sum) >> shg_sum;
                pixels[yi + 2] = (b_sum * mul_sum) >> shg_sum;
                r_sum -= r_out_sum;
                g_sum -= g_out_sum;
                b_sum -= b_out_sum;
                r_out_sum -= stackIn.r;
                g_out_sum -= stackIn.g;
                b_out_sum -= stackIn.b;
                p = (yw + ((p = x + radius + 1) < widthMinus1 ? p : widthMinus1)) << 2;
                r_in_sum += (stackIn.r = pixels[p]);
                g_in_sum += (stackIn.g = pixels[p + 1]);
                b_in_sum += (stackIn.b = pixels[p + 2]);
                r_sum += r_in_sum;
                g_sum += g_in_sum;
                b_sum += b_in_sum;
                stackIn = stackIn.next;
                r_out_sum += (pr = stackOut.r);
                g_out_sum += (pg = stackOut.g);
                b_out_sum += (pb = stackOut.b);
                r_in_sum -= pr;
                g_in_sum -= pg;
                b_in_sum -= pb;
                stackOut = stackOut.next;
                yi += 4;
            }
            yw += width;
        }
        for( x = 0; 0 <= width ? x < width : x > width; 0 <= width ? x++ : x-- ) {
            g_in_sum = b_in_sum = r_in_sum = g_sum = b_sum = r_sum = 0;
            yi = x << 2;
            r_out_sum = radiusPlus1 * (pr = pixels[yi]);
            g_out_sum = radiusPlus1 * (pg = pixels[yi + 1]);
            b_out_sum = radiusPlus1 * (pb = pixels[yi + 2]);
            r_sum += sumFactor * pr;
            g_sum += sumFactor * pg;
            b_sum += sumFactor * pb;
            stack = stackStart;
            for( i = 0; 0 <= radiusPlus1 ? i < radiusPlus1 : i > radiusPlus1; 0 <= radiusPlus1 ? i++ : i-- ) {
                stack.r = pr;
                stack.g = pg;
                stack.b = pb;
                stack = stack.next;
            }
            yp = width;
            for( i = 1; 1 <= radius ? i <= radius : i >= radius; 1 <= radius ? i++ : i-- ) {
                yi = (yp + x) << 2;
                r_sum += (stack.r = (pr = pixels[yi])) * (rbs = radiusPlus1 - i);
                g_sum += (stack.g = (pg = pixels[yi + 1])) * rbs;
                b_sum += (stack.b = (pb = pixels[yi + 2])) * rbs;
                r_in_sum += pr;
                g_in_sum += pg;
                b_in_sum += pb;
                stack = stack.next;
                if( i < heightMinus1 ) {
                    yp += width;
                }
            }
            yi = x;
            stackIn = stackStart;
            stackOut = stackEnd;
            for( y = 0; 0 <= height ? y < height : y > height; 0 <= height ? y++ : y-- ) {
                p = yi << 2;
                pixels[p] = (r_sum * mul_sum) >> shg_sum;
                pixels[p + 1] = (g_sum * mul_sum) >> shg_sum;
                pixels[p + 2] = (b_sum * mul_sum) >> shg_sum;
                r_sum -= r_out_sum;
                g_sum -= g_out_sum;
                b_sum -= b_out_sum;
                r_out_sum -= stackIn.r;
                g_out_sum -= stackIn.g;
                b_out_sum -= stackIn.b;
                p = (x + (((p = y + radiusPlus1) < heightMinus1 ? p : heightMinus1) * width)) << 2;
                r_sum += (r_in_sum += (stackIn.r = pixels[p]));
                g_sum += (g_in_sum += (stackIn.g = pixels[p + 1]));
                b_sum += (b_in_sum += (stackIn.b = pixels[p + 2]));
                stackIn = stackIn.next;
                r_out_sum += (pr = stackOut.r);
                g_out_sum += (pg = stackOut.g);
                b_out_sum += (pb = stackOut.b);
                r_in_sum -= pr;
                g_in_sum -= pg;
                b_in_sum -= pb;
                stackOut = stackOut.next;
                yi += width;
            }
        }

    }

    function applyLayer( layer, data, layerData, width, height, layerArr ) {

        var filters = layer[1];
        var filterLen = filters.length;
        var opacity = 100;
        var r, g, b, a;
        for( var k = 0; k < filterLen; k++ ) {
            if( typeof filters[k] === 'string' ) {
                var options = filters[k].split( ':' );
                if( options[0] == 'stackBlur' ) {
                    fabric.imgutil.stackBlur( layerData, width, height, parseInt( options[1] ) );
                } else if( options[0] == 'opacity' ) {
                    opacity = parseInt( options[1] ) / 100;
                } else if( options[0] == "sharpen" ) {
                    var func = fabric.imgutil.sharpen( parseInt( options[1] ) );
                    func( data, width, height );
                }
                continue;
            }
            if( typeof filters[k] === 'number' ) {

                fabric.imgutil.applyLayer( layerArr[filters[k]].filter, layerData, layerArr[filters[k]].layer.pixels, width, height, layer );
                continue;
            }

            for( var i = 0, len = layerData.length; i < len; i += 4 ) {

                r = layerData[i];
                g = layerData[i + 1];
                b = layerData[i + 2];
                a = layerData[i + 3];

                var rgba = {
                    r: r,
                    g: g,
                    b: b,
                    a: a
                }

                rgba = filters[k]( rgba );

                layerData[i] = rgba.r;
                layerData[i + 1] = rgba.g;
                layerData[i + 2] = rgba.b;
                layerData[i + 3] = rgba.a;
            }

        }
        var parentData, result, rgbaLayer, rgbaParent, _ref, _results;

        for( i = 0, _ref = layerData.length; i < _ref; i += 4 ) {

            rgbaParent = {
                r: data[i],
                g: data[i + 1],
                b: data[i + 2],
                a: data[i + 3]
            };

            rgbaLayer = {
                r: layerData[i],
                g: layerData[i + 1],
                b: layerData[i + 2],
                a: layerData[i + 3]
            };

            result = layer[0]( rgbaLayer, rgbaParent );
            result.r = Util.clampRGB( result.r );
            result.g = Util.clampRGB( result.g );
            result.b = Util.clampRGB( result.b );
            if( !(result.a != null) ) {
                result.a = rgbaLayer.a;
            }
            data[i] = rgbaParent.r - ((rgbaParent.r - result.r) * (opacity * (result.a / 255)));
            data[i + 1] = rgbaParent.g - ((rgbaParent.g - result.g) * (opacity * (result.a / 255)));
            data[i + 2] = rgbaParent.b - ((rgbaParent.b - result.b) * (opacity * (result.a / 255)));
        }

    }

    function addLayer( data, width, height ) {

        var newlayer = fabric.document.createElement( 'canvas' ), layerContext = newlayer.getContext( '2d' );
        newlayer.width = width;
        newlayer.height = height;
        layerContext.createImageData( newlayer.width, newlayer.height );

        var layerImageData = layerContext.getImageData( 0, 0, width, height );
        var layerData = layerImageData.data;

        var l, _ref;
        for( l = 0, _ref = data.length; l < _ref; l += 4 ) {
            layerData[l] = data[l];
            layerData[l + 1] = data[l + 1];
            layerData[l + 2] = data[l + 2];
            layerData[l + 3] = data[l + 3];
        }

        return  {
            layer: newlayer,
            pixels: layerData
        }

    }

    function renderKernel( job ) {
        var adjust, adjustSize, bias, builder, builderIndex, divisor, end, i, j, k, kernel, modPixelData, n, name, pixel, pixelInfo, res, start;
        var width, height;
        width = job.width;
        height = job.height;
        bias = job.bias;
        divisor = job.divisor;
        n = job.pixelData.length;
        adjust = job.adjust;
        adjustSize = Math.sqrt( adjust.length );
        kernel = [];
        modPixelData = [];
        start = width * 4 * ((adjustSize - 1) / 2);
        end = n - (height * 4 * ((adjustSize - 1) / 2));
        builder = (adjustSize - 1) / 2;
        pixelInfo = new PixelInfo( job.pixelData, width, height );
        for( i = start; i < end; i += 4 ) {
            pixelInfo.loc = i;
            builderIndex = 0;
            for( j = -builder; -builder <= builder ? j <= builder : j >= builder; -builder <= builder ? j++ : j-- ) {
                for( k = builder; builder <= -builder ? k <= -builder : k >= -builder; builder <= -builder ? k++ : k-- ) {
                    pixel = pixelInfo.getPixelRelative( j, k );
                    kernel[builderIndex * 3] = pixel.r;
                    kernel[builderIndex * 3 + 1] = pixel.g;
                    kernel[builderIndex * 3 + 2] = pixel.b;
                    builderIndex++;
                }
            }
            res = processKernel( adjust, kernel, divisor, bias );
            modPixelData[i] = Util.clampRGB( res.r );
            modPixelData[i + 1] = Util.clampRGB( res.g );
            modPixelData[i + 2] = Util.clampRGB( res.b );
            modPixelData[i + 3] = job.pixelData[i + 3];
        }
        for( i = start; start <= end ? i < end : i > end; start <= end ? i++ : i-- ) {
            job.pixelData[i] = modPixelData[i];
        }

    }

    function processKernel( adjust, kernel, divisor, bias ) {
        var i, val, _ref;
        val = {
            r: 0,
            g: 0,
            b: 0
        };
        for( i = 0, _ref = adjust.length; 0 <= _ref ? i < _ref : i > _ref; 0 <= _ref ? i++ : i-- ) {
            val.r += adjust[i] * kernel[i * 3];
            val.g += adjust[i] * kernel[i * 3 + 1];
            val.b += adjust[i] * kernel[i * 3 + 2];
        }
        val.r = (val.r / divisor) + bias;
        val.g = (val.g / divisor) + bias;
        val.b = (val.b / divisor) + bias;
        return val;
    }

    function sharpen( amt ) {
        if( amt == null ) {
            amt = 100;
        }
        amt /= 100;

        return real_processKernel( "Sharpen", [0, -amt, 0, -amt, 4 * amt + 1, -amt, 0, -amt, 0] );
    }

    function real_processKernel( name, adjust, divisor, bias ) {
        var i, _ref;
        if( !divisor ) {
            divisor = 0;
            for( i = 0, _ref = adjust.length; 0 <= _ref ? i < _ref : i > _ref; 0 <= _ref ? i++ : i-- ) {
                divisor += adjust[i];
            }
        }

        var process = function( data, width, height ) {
            renderKernel( {
                              width: width,
                              height: height,
                              bias: bias || 0,
                              divisor: divisor,
                              pixelData: data,
                              adjust: adjust
                          } );
        };
        return process;
    }

    function renderFilters( data, width, height, processfunc, layer ) {
        var r, g, b;
        var funcLen = processfunc.length;
        for( var j = 0; j < funcLen; j++ ) {

            if( typeof processfunc[j] === 'string' ) {
                var filter = processfunc[j].split( ':' );
                if( filter[0] == "vignette" ) {
                    fabric.imgutil.vignette( filter[1], filter[2], data, width, height );
                } else if( filter[0] == "sharpen" ) {
                    var func = fabric.imgutil.sharpen( parseInt( filter[1] ) );

                    func( data, width, height );
                }
                continue;
            }
            if( typeof processfunc[j] === 'number' ) {

                fabric.imgutil.applyLayer( layer[processfunc[j]].filter, data, layer[processfunc[j]].layer.pixels, width, height, layer );
                continue;
            }
            for( var i = 0, len = data.length; i < len; i += 4 ) {

                r = data[i];
                g = data[i + 1];
                b = data[i + 2];
                var rgba = {r: r, g: g, b: b}
                rgba = processfunc[j]( rgba );
                data[i] = rgba.r;
                data[i + 1] = rgba.g;
                data[i + 2] = rgba.b;

            }
        }
    }

    function hue( adjust ) {
        var process = function( rgba ) {
            var h, hsv, rgb;
            hsv = Convert.rgbToHSV( rgba.r, rgba.g, rgba.b );
            h = hsv.h * 100;
            h += Math.abs( adjust );
            h = h % 100;
            h /= 100;
            hsv.h = h;
            rgb = Convert.hsvToRGB( hsv.h, hsv.s, hsv.v );
            rgb.a = rgba.a;
            return rgb;
        };
        return process;
    }

    fabric.imgutil.contrast = contrast;
    fabric.imgutil.noise = noise;
    fabric.imgutil.sepia = sepia;
    fabric.imgutil.channels = channels;
    fabric.imgutil.gamma = gamma;
    fabric.imgutil.greyscale = greyscale;
    fabric.imgutil.vignette = vignette;
    fabric.imgutil.exposure = exposure;
    fabric.imgutil.saturation = saturation;
    fabric.imgutil.vibrance = vibrance;
    fabric.imgutil.curves = curves;
    fabric.imgutil.exposure = exposure;
    fabric.imgutil.colorize = colorize;
    fabric.imgutil.brightness = brightness;
    fabric.imgutil.posterize = posterize;
    fabric.imgutil.clip = clip;
    fabric.imgutil.fillColor = fillColor;
    fabric.imgutil.applyLayer = applyLayer;
    fabric.imgutil.stackBlur = stackBlur;
    fabric.imgutil.addLayer = addLayer;
    fabric.imgutil.renderKernel = renderKernel;
    fabric.imgutil.sharpen = sharpen;
    fabric.imgutil.renderFilters = renderFilters;
    fabric.imgutil.hue = hue;
})();
(function() {

    PixelInfo = function( c, width, height ) {
        this.pixelData = c;
        this.loc = 0;
        this.width = width;
        this.height = height;
    }

    PixelInfo.prototype.locationXY = function() {
        var x, y;
        y = this.height - Math.floor( this.loc / (this.width * 4) );
        x = (this.loc % (this.width * 4)) / 4;
        return {
            x: x,
            y: y
        };
    }

    PixelInfo.prototype.getPixelRelative = function( horiz, vert ) {
        var newLoc;
        newLoc = this.loc + (this.width * 4 * (vert * -1)) + (4 * horiz);
        if( newLoc > this.pixelData.length || newLoc < 0 ) {
            return {
                r: 0,
                g: 0,
                b: 0,
                a: 0
            };
        }
        return {
            r: this.pixelData[newLoc],
            g: this.pixelData[newLoc + 1],
            b: this.pixelData[newLoc + 2],
            a: this.pixelData[newLoc + 3]
        };
    }

    PixelInfo.prototype.getPixel = function( x, y ) {
        var loc;
        loc = (y * this.width + x) * 4;
        return {
            r: this.pixelData[loc],
            g: this.pixelData[loc + 1],
            b: this.pixelData[loc + 2],
            a: this.pixelData[loc + 3]
        };
    }

    PixelInfo.prototype.putPixel = function( x, y, rgba ) {
        var loc;
        loc = (y * this.width + x) * 4;
        this.pixelData[loc] = rgba.r;
        this.pixelData[loc + 1] = rgba.g;
        this.pixelData[loc + 2] = rgba.b;
        return this.pixelData[loc + 3] = rgba.a;
    };
})();
(function() {
    Util = {}

    Util.clampRGB = function( val ) {
        if( val < 0 ) {
            return 0;
        }
        if( val > 255 ) {
            return 255;
        }
        return val;
    }

})();
(function() {
    Blender = {}

    Blender.normal = function( rgbaLayer, rgbaParent ) {
        return {
            r: rgbaLayer.r,
            g: rgbaLayer.g,
            b: rgbaLayer.b
        };
    }

    Blender.multiply = function( rgbaLayer, rgbaParent ) {
        return {
            r: (rgbaLayer.r * rgbaParent.r) / 255,
            g: (rgbaLayer.g * rgbaParent.g) / 255,
            b: (rgbaLayer.b * rgbaParent.b) / 255
        };
    }

    Blender.overlay = function( rgbaLayer, rgbaParent ) {
        var result;
        result = {};
        result.r = rgbaParent.r > 128 ? 255 - 2 * (255 - rgbaLayer.r) * (255 - rgbaParent.r) / 255 : (rgbaParent.r * rgbaLayer.r * 2) / 255;
        result.g = rgbaParent.g > 128 ? 255 - 2 * (255 - rgbaLayer.g) * (255 - rgbaParent.g) / 255 : (rgbaParent.g * rgbaLayer.g * 2) / 255;
        result.b = rgbaParent.b > 128 ? 255 - 2 * (255 - rgbaLayer.b) * (255 - rgbaParent.b) / 255 : (rgbaParent.b * rgbaLayer.b * 2) / 255;
        return result;
    }
    Blender.screen = function( rgbaLayer, rgbaParent ) {
        return {
            r: 255 - (((255 - rgbaLayer.r) * (255 - rgbaParent.r)) / 255),
            g: 255 - (((255 - rgbaLayer.g) * (255 - rgbaParent.g)) / 255),
            b: 255 - (((255 - rgbaLayer.b) * (255 - rgbaParent.b)) / 255)
        };
    }

    Blender.difference = function( rgbaLayer, rgbaParent ) {
        return {
            r: rgbaLayer.r - rgbaParent.r,
            g: rgbaLayer.g - rgbaParent.g,
            b: rgbaLayer.b - rgbaParent.b
        };
    }

    Blender.addition = function( rgbaLayer, rgbaParent ) {
        return {
            r: rgbaParent.r + rgbaLayer.r,
            g: rgbaParent.g + rgbaLayer.g,
            b: rgbaParent.b + rgbaLayer.b
        };
    }

    Blender.exclusion = function( rgbaLayer, rgbaParent ) {
        return {
            r: 128 - 2 * (rgbaParent.r - 128) * (rgbaLayer.r - 128) / 255,
            g: 128 - 2 * (rgbaParent.g - 128) * (rgbaLayer.g - 128) / 255,
            b: 128 - 2 * (rgbaParent.b - 128) * (rgbaLayer.b - 128) / 255
        };
    }

    Blender.softLight = function( rgbaLayer, rgbaParent ) {
        var result;
        result = {};
        result.r = rgbaParent.r > 128 ? 255 - ((255 - rgbaParent.r) * (255 - (rgbaLayer.r - 128))) / 255 : (rgbaParent.r * (rgbaLayer.r + 128)) / 255;
        result.g = rgbaParent.g > 128 ? 255 - ((255 - rgbaParent.g) * (255 - (rgbaLayer.g - 128))) / 255 : (rgbaParent.g * (rgbaLayer.g + 128)) / 255;
        result.b = rgbaParent.b > 128 ? 255 - ((255 - rgbaParent.b) * (255 - (rgbaLayer.b - 128))) / 255 : (rgbaParent.b * (rgbaLayer.b + 128)) / 255;
        return result;
    }

    Blender.lighten = function( rgbaLayer, rgbaParent ) {
        return {
            r: rgbaParent.r > rgbaLayer.r ? rgbaParent.r : rgbaLayer.r,
            g: rgbaParent.g > rgbaLayer.g ? rgbaParent.g : rgbaLayer.g,
            b: rgbaParent.b > rgbaLayer.b ? rgbaParent.b : rgbaLayer.b
        };
    }

    Blender.darken = function( rgbaLayer, rgbaParent ) {
        return {
            r: rgbaParent.r > rgbaLayer.r ? rgbaLayer.r : rgbaParent.r,
            g: rgbaParent.g > rgbaLayer.g ? rgbaLayer.g : rgbaParent.g,
            b: rgbaParent.b > rgbaLayer.b ? rgbaLayer.b : rgbaParent.b
        };
    }

})();
(function() {
    Convert = {}

    Convert.hexToRGB = function( hex ) {
        var b, g, r;
        if( hex.charAt( 0 ) === "#" ) {
            hex = hex.substr( 1 );
        }
        r = parseInt( hex.substr( 0, 2 ), 16 );
        g = parseInt( hex.substr( 2, 2 ), 16 );
        b = parseInt( hex.substr( 4, 2 ), 16 );
        return {
            r: r,
            g: g,
            b: b
        };
    };

    Convert.rgbToHSV = function( r, g, b ) {
        var d, h, max, min, s, v;
        r /= 255;
        g /= 255;
        b /= 255;
        max = Math.max( r, g, b );
        min = Math.min( r, g, b );
        v = max;
        d = max - min;
        s = max === 0 ? 0 : d / max;
        if( max === min ) {
            h = 0;
        } else {
            h = (function() {
                switch( max ) {
                    case r:
                        return (g - b) / d + (g < b ? 6 : 0);
                    case g:
                        return (b - r) / d + 2;
                    case b:
                        return (r - g) / d + 4;
                }
            })();
            h /= 6;
        }
        return {
            h: h,
            s: s,
            v: v
        };
    };

    Convert.hsvToRGB = function( h, s, v ) {
        var b, f, g, i, p, q, r, t;
        i = Math.floor( h * 6 );
        f = h * 6 - i;
        p = v * (1 - s);
        q = v * (1 - f * s);
        t = v * (1 - (1 - f) * s);
        switch( i % 6 ) {
            case 0:
                r = v;
                g = t;
                b = p;
                break;
            case 1:
                r = q;
                g = v;
                b = p;
                break;
            case 2:
                r = p;
                g = v;
                b = t;
                break;
            case 3:
                r = p;
                g = q;
                b = v;
                break;
            case 4:
                r = t;
                g = p;
                b = v;
                break;
            case 5:
                r = v;
                g = p;
                b = q;
        }
        return {
            r: r * 255,
            g: g * 255,
            b: b * 255
        };
    };

})();
(function() {

    Calculate = {}

    Calculate.distance = function( x1, y1, x2, y2 ) {
        return Math.sqrt( Math.pow( x2 - x1, 2 ) + Math.pow( y2 - y1, 2 ) );
    };

    Calculate.randomRange = function( min, max, getFloat ) {
        var rand;
        if( getFloat == null ) {
            getFloat = false;
        }
        rand = min + (Math.random() * (max - min));
        if( getFloat ) {
            return rand.toFixed( getFloat );
        } else {
            return Math.round( rand );
        }
    };

    Calculate.bezier = function( start, ctrl1, ctrl2, end, lowBound, highBound ) {
        var Ax, Ay, Bx, By, Cx, Cy, bezier, curveX, curveY, i, j, leftCoord, rightCoord, t, x0, x1, x2, x3, y0, y1, y2, y3, _ref, _ref2;
        x0 = start[0];
        y0 = start[1];
        x1 = ctrl1[0];
        y1 = ctrl1[1];
        x2 = ctrl2[0];
        y2 = ctrl2[1];
        x3 = end[0];
        y3 = end[1];
        bezier = {};
        Cx = 3 * (x1 - x0);
        Bx = 3 * (x2 - x1) - Cx;
        Ax = x3 - x0 - Cx - Bx;
        Cy = 3 * (y1 - y0);
        By = 3 * (y2 - y1) - Cy;
        Ay = y3 - y0 - Cy - By;
        for( i = 0; i < 1000; i++ ) {
            t = i / 1000;
            curveX = Math.round( (Ax * Math.pow( t, 3 )) + (Bx * Math.pow( t, 2 )) + (Cx * t) + x0 );
            curveY = Math.round( (Ay * Math.pow( t, 3 )) + (By * Math.pow( t, 2 )) + (Cy * t) + y0 );
            if( lowBound && curveY < lowBound ) {
                curveY = lowBound;
            } else if( highBound && curveY > highBound ) {
                curveY = highBound;
            }
            bezier[curveX] = curveY;
        }
        if( bezier.length < end[0] + 1 ) {
            for( i = 0, _ref = end[0]; 0 <= _ref ? i <= _ref : i >= _ref; 0 <= _ref ? i++ : i-- ) {
                if( !(bezier[i] != null) ) {
                    leftCoord = [i - 1, bezier[i - 1]];
                    for( j = i, _ref2 = end[0]; i <= _ref2 ? j <= _ref2 : j >= _ref2; i <= _ref2 ? j++ : j-- ) {
                        if( bezier[j] != null ) {
                            rightCoord = [j, bezier[j]];
                            break;
                        }
                    }
                    bezier[i] = leftCoord[1] + ((rightCoord[1] - leftCoord[1]) / (rightCoord[0] - leftCoord[0])) * (i - leftCoord[0]);
                }
            }
        }
        if( !(bezier[end[0]] != null) ) {
            bezier[end[0]] = bezier[end[0] - 1];
        }
        return bezier;
    };

})();
fabric.Canvas.prototype.getAbsoluteCoords = function( object ) {
    return ( { left: object.left + this._offset.left - object.width / 2, top: object.top + this._offset.top + object.height / 2 } );
}
/**
 * Dav END
 * FabricJS extenstions
 **/

/**
 * BEGIN ADDITIONAL FILTERS
 **/
fabric.Image.filters.vintage = fabric.util.createClass( {

                                                            /**
                                                             * @param {String} type
                                                             */
                                                            type: "vintage",

                                                            /**
                                                             * @memberOf fabric.Image.filters.RemoveWhite.prototype
                                                             * @param {Object} [options] Options object

                                                             /**
                                                             * @method applyTo
                                                             * @param {Object} canvasEl Canvas element to apply filter to
                                                             */
                                                            applyTo: function( canvasEl ) {

                                                                var context = canvasEl.getContext( '2d' ), imageData = context.getImageData( 0, 0, canvasEl.width,
                                                                                                                                             canvasEl.height ), data = imageData.data, width = canvasEl.width, height = canvasEl.height, r, g, b;

                                                                var processfunc = [];
                                                                processfunc.push( fabric.imgutil.greyscale() );
                                                                processfunc.push( fabric.imgutil.contrast( 5 ) );
                                                                processfunc.push( fabric.imgutil.noise( 3 ) );
                                                                processfunc.push( fabric.imgutil.sepia( 100 ) );
                                                                processfunc.push( fabric.imgutil.channels( {
                                                                                                               red: 8,
                                                                                                               blue: 2,
                                                                                                               green: 4
                                                                                                           } ) );
                                                                processfunc.push( fabric.imgutil.gamma( 0.87 ) );

                                                                var funcLen = processfunc.length;
                                                                for( var i = 0, len = data.length; i < len; i += 4 ) {

                                                                    r = data[i];
                                                                    g = data[i + 1];
                                                                    b = data[i + 2];
                                                                    var rgba = {r: r, g: g, b: b}
                                                                    for( var j = 0; j < funcLen; j++ ) {
                                                                        rgba = processfunc[j]( rgba );
                                                                    }
                                                                    //            var rgb = fabric.imgutil.greyscale({r:r, g:g,b:b});
                                                                    //            rgb = fabric.imgutil.contrast(rgb, 5);
                                                                    //            rgb = fabric.imgutil.noise(rgb, 3);
                                                                    //            rgb = fabric.imgutil.sepia(rgb, 100);
                                                                    //            rgb = fabric.imgutil.channels(rgb, {
                                                                    //                red: 8,
                                                                    //                blue: 2,
                                                                    //                green: 4
                                                                    //                });
                                                                    //            rgb = fabric.imgutil.gamma(rgb, 0.87);
                                                                    data[i] = rgba.r;
                                                                    data[i + 1] = rgba.g;
                                                                    data[i + 2] = rgba.b;

                                                                }
                                                                fabric.imgutil.vignette( "40%", 30, data, width, height );
                                                                //        for (i = 0, len = data1.length; i < len; i ++) {
                                                                //            data[i] = data1[i];
                                                                //        }
                                                                context.putImageData( imageData, 0, 0 );

                                                            },

                                                            /**
                                                             * @method toJSON
                                                             * @return {String} json representation of filter
                                                             */
                                                            toJSON: function() {
                                                                return {
                                                                    type: this.type
                                                                };
                                                            }
                                                        } );
fabric.Image.filters.vintage.fromObject = function( object ) {
    return new fabric.Image.filters.vintage( object );
};

fabric.Image.filters.sunrise = fabric.util.createClass( {

                                                            /**
                                                             * @param {String} type
                                                             */
                                                            type: "sunrise",

                                                            /**
                                                             * @memberOf fabric.Image.filters.RemoveWhite.prototype
                                                             * @param {Object} [options] Options object

                                                             /**
                                                             * @method applyTo
                                                             * @param {Object} canvasEl Canvas element to apply filter to
                                                             */
                                                            applyTo: function( canvasEl ) {

                                                                var context = canvasEl.getContext( '2d' ), imageData = context.getImageData( 0, 0, canvasEl.width,
                                                                                                                                             canvasEl.height ), data = imageData.data, width = canvasEl.width, height = canvasEl.height, r, g, b;
                                                                var processfunc = [];
                                                                processfunc.push( fabric.imgutil.exposure( 3.5 ) );
                                                                processfunc.push( fabric.imgutil.saturation( -5 ) );
                                                                processfunc.push( fabric.imgutil.vibrance( 50 ) );
                                                                processfunc.push( fabric.imgutil.sepia( 60 ) );
                                                                processfunc.push( fabric.imgutil.colorize( "#e87b22", 10 ) );
                                                                processfunc.push( fabric.imgutil.channels( {
                                                                                                               red: 8,
                                                                                                               blue: 8
                                                                                                           } ) );
                                                                processfunc.push( fabric.imgutil.contrast( 5 ) );
                                                                processfunc.push( fabric.imgutil.gamma( 1.2 ) );
                                                                //
                                                                var funcLen = processfunc.length;

                                                                for( var i = 0, len = data.length; i < len; i += 4 ) {

                                                                    r = data[i];
                                                                    g = data[i + 1];
                                                                    b = data[i + 2];
                                                                    var rgba = {r: r, g: g, b: b}
                                                                    for( var j = 0; j < funcLen; j++ ) {
                                                                        rgba = processfunc[j]( rgba );
                                                                    }
                                                                    data[i] = rgba.r;
                                                                    data[i + 1] = rgba.g;
                                                                    data[i + 2] = rgba.b;

                                                                }

                                                                fabric.imgutil.vignette( "55%", 25, data, width, height );
                                                                //        for (i = 0, len = data1.length; i < len; i ++) {
                                                                //            data[i] = data1[i];
                                                                //        }
                                                                context.putImageData( imageData, 0, 0 );

                                                            },

                                                            /**
                                                             * @method toJSON
                                                             * @return {String} json representation of filter
                                                             */
                                                            toJSON: function() {
                                                                return {
                                                                    type: this.type
                                                                };
                                                            }



                                                        } );
fabric.Image.filters.sunrise.fromObject = function( object ) {
    return new fabric.Image.filters.sunrise( object );
};

fabric.Image.filters.sinCity = fabric.util.createClass( {

                                                            /**
                                                             * @param {String} type
                                                             */
                                                            type: "sinCity",

                                                            /**
                                                             * @memberOf fabric.Image.filters.RemoveWhite.prototype
                                                             * @param {Object} [options] Options object

                                                             /**
                                                             * @method applyTo
                                                             * @param {Object} canvasEl Canvas element to apply filter to
                                                             */
                                                            applyTo: function( canvasEl ) {

                                                                var context = canvasEl.getContext( '2d' ), imageData = context.getImageData( 0, 0, canvasEl.width,
                                                                                                                                             canvasEl.height ), data = imageData.data, width = canvasEl.width, height = canvasEl.height, r, g, b, a;
                                                                var processfunc = [];

                                                                processfunc.push( fabric.imgutil.contrast( 100 ) );
                                                                processfunc.push( fabric.imgutil.brightness( 15 ) );
                                                                processfunc.push( fabric.imgutil.exposure( 10 ) );
                                                                processfunc.push( fabric.imgutil.posterize( 80 ) );
                                                                processfunc.push( fabric.imgutil.clip( 30 ) );
                                                                processfunc.push( fabric.imgutil.greyscale() );

                                                                var funcLen = processfunc.length;
                                                                for( var j = 0; j < funcLen; j++ ) {
                                                                    for( var i = 0, len = data.length; i < len; i += 4 ) {

                                                                        r = data[i];
                                                                        g = data[i + 1];
                                                                        b = data[i + 2];
                                                                        a = data[i + 3];

                                                                        var rgba = {r: r, g: g, b: b, a: a}

                                                                        rgba = processfunc[j]( rgba );

                                                                        data[i] = rgba.r;
                                                                        data[i + 1] = rgba.g;
                                                                        data[i + 2] = rgba.b;
                                                                        data[i + 3] = rgba.a;

                                                                    }
                                                                }
                                                                context.putImageData( imageData, 0, 0 );

                                                            },

                                                            /**
                                                             * @method toJSON
                                                             * @return {String} json representation of filter
                                                             */
                                                            toJSON: function() {
                                                                return {
                                                                    type: this.type
                                                                };
                                                            }

                                                        } );
fabric.Image.filters.sinCity.fromObject = function( object ) {
    return new fabric.Image.filters.sinCity( object );
};

fabric.Image.filters.pinhole = fabric.util.createClass( {

                                                            /**
                                                             * @param {String} type
                                                             */
                                                            type: "pinhole",

                                                            /**
                                                             * @memberOf fabric.Image.filters.RemoveWhite.prototype
                                                             * @param {Object} [options] Options object

                                                             /**
                                                             * @method applyTo
                                                             * @param {Object} canvasEl Canvas element to apply filter to
                                                             */
                                                            applyTo: function( canvasEl ) {

                                                                var context = canvasEl.getContext( '2d' ), imageData = context.getImageData( 0, 0, canvasEl.width,
                                                                                                                                             canvasEl.height ), data = imageData.data, width = canvasEl.width, height = canvasEl.height, r, g, b, a;
                                                                var processfunc = [];

                                                                processfunc.push( fabric.imgutil.greyscale() );
                                                                processfunc.push( fabric.imgutil.sepia( 10 ) );
                                                                processfunc.push( fabric.imgutil.exposure( 10 ) );
                                                                processfunc.push( fabric.imgutil.contrast( 15 ) );

                                                                var funcLen = processfunc.length;
                                                                for( var j = 0; j < funcLen; j++ ) {
                                                                    for( var i = 0, len = data.length; i < len; i += 4 ) {

                                                                        r = data[i];
                                                                        g = data[i + 1];
                                                                        b = data[i + 2];
                                                                        a = data[i + 3];

                                                                        var rgba = {r: r, g: g, b: b, a: a}
                                                                        rgba = processfunc[j]( rgba );

                                                                        data[i] = rgba.r;
                                                                        data[i + 1] = rgba.g;
                                                                        data[i + 2] = rgba.b;
                                                                        data[i + 3] = rgba.a;

                                                                    }
                                                                }
                                                                fabric.imgutil.vignette( "60%", 35, data, width, height );
                                                                context.putImageData( imageData, 0, 0 );
                                                            },

                                                            /**
                                                             * @method toJSON
                                                             * @return {String} json representation of filter
                                                             */
                                                            toJSON: function() {
                                                                return {
                                                                    type: this.type
                                                                };
                                                            }

                                                        } );
fabric.Image.filters.pinhole.fromObject = function( object ) {
    return new fabric.Image.filters.pinhole( object );
};

fabric.Image.filters.orangePeel = fabric.util.createClass( {

                                                               /**
                                                                * @param {String} type
                                                                */
                                                               type: "orangePeel",

                                                               /**
                                                                * @memberOf fabric.Image.filters.RemoveWhite.prototype
                                                                * @param {Object} [options] Options object

                                                                /**
                                                                * @method applyTo
                                                                * @param {Object} canvasEl Canvas element to apply filter to
                                                                */
                                                               applyTo: function( canvasEl ) {

                                                                   var context = canvasEl.getContext( '2d' ), imageData = context.getImageData( 0, 0, canvasEl.width,
                                                                                                                                                canvasEl.height ), data = imageData.data, width = canvasEl.width, height = canvasEl.height, r, g, b, a;
                                                                   var processfunc = [];

                                                                   processfunc.push( fabric.imgutil.curves( 'rgb', [0, 0], [100, 50], [140, 200], [255, 255] ) );
                                                                   processfunc.push( fabric.imgutil.vibrance( -30 ) );
                                                                   processfunc.push( fabric.imgutil.saturation( -30 ) );
                                                                   processfunc.push( fabric.imgutil.colorize( '#ff9000', 30 ) );
                                                                   processfunc.push( fabric.imgutil.contrast( -5 ) );
                                                                   processfunc.push( fabric.imgutil.gamma( 1.4 ) );

                                                                   var funcLen = processfunc.length;
                                                                   for( var j = 0; j < funcLen; j++ ) {
                                                                       for( var i = 0, len = data.length; i < len; i += 4 ) {

                                                                           r = data[i];
                                                                           g = data[i + 1];
                                                                           b = data[i + 2];
                                                                           a = data[i + 3];

                                                                           var rgba = {r: r, g: g, b: b, a: a}
                                                                           rgba = processfunc[j]( rgba );

                                                                           data[i] = rgba.r;
                                                                           data[i + 1] = rgba.g;
                                                                           data[i + 2] = rgba.b;
                                                                           data[i + 3] = rgba.a;

                                                                       }
                                                                   }

                                                                   context.putImageData( imageData, 0, 0 );
                                                               },

                                                               /**
                                                                * @method toJSON
                                                                * @return {String} json representation of filter
                                                                */
                                                               toJSON: function() {
                                                                   return {
                                                                       type: this.type
                                                                   };
                                                               }

                                                           } );
fabric.Image.filters.orangePeel.fromObject = function( object ) {
    return new fabric.Image.filters.orangePeel( object );
};

fabric.Image.filters.oldBoot = fabric.util.createClass( {

                                                            /**
                                                             * @param {String} type
                                                             */
                                                            type: "oldBoot",

                                                            /**
                                                             * @memberOf fabric.Image.filters.RemoveWhite.prototype
                                                             * @param {Object} [options] Options object

                                                             /**
                                                             * @method applyTo
                                                             * @param {Object} canvasEl Canvas element to apply filter to
                                                             */
                                                            applyTo: function( canvasEl ) {

                                                                var context = canvasEl.getContext( '2d' ), imageData = context.getImageData( 0, 0, canvasEl.width,
                                                                                                                                             canvasEl.height ), data = imageData.data, width = canvasEl.width, height = canvasEl.height, r, g, b, a;
                                                                var processfunc = [];

                                                                processfunc.push( fabric.imgutil.saturation( -20 ) );
                                                                processfunc.push( fabric.imgutil.vibrance( -50 ) );
                                                                processfunc.push( fabric.imgutil.gamma( 1.1 ) );
                                                                processfunc.push( fabric.imgutil.sepia( 30 ) );
                                                                processfunc.push( fabric.imgutil.channels( {
                                                                                                               red: -10,
                                                                                                               blue: 5
                                                                                                           } ) );
                                                                processfunc.push( fabric.imgutil.curves( 'rgb', [0, 0], [80, 50], [128, 230], [255, 255] ) );

                                                                var funcLen = processfunc.length;
                                                                for( var j = 0; j < funcLen; j++ ) {

                                                                    for( var i = 0, len = data.length; i < len; i += 4 ) {

                                                                        r = data[i];
                                                                        g = data[i + 1];
                                                                        b = data[i + 2];
                                                                        a = data[i + 3];

                                                                        var rgba = {r: r, g: g, b: b, a: a}
                                                                        rgba = processfunc[j]( rgba );

                                                                        data[i] = rgba.r;
                                                                        data[i + 1] = rgba.g;
                                                                        data[i + 2] = rgba.b;
                                                                        data[i + 3] = rgba.a;

                                                                    }
                                                                }
                                                                fabric.imgutil.vignette( "60%", 30, data, width, height );
                                                                context.putImageData( imageData, 0, 0 );
                                                            },

                                                            /**
                                                             * @method toJSON
                                                             * @return {String} json representation of filter
                                                             */
                                                            toJSON: function() {
                                                                return {
                                                                    type: this.type
                                                                };
                                                            }

                                                        } );
fabric.Image.filters.oldBoot.fromObject = function( object ) {
    return new fabric.Image.filters.oldBoot( object );
};

fabric.Image.filters.nostalgia = fabric.util.createClass( {

                                                              /**
                                                               * @param {String} type
                                                               */
                                                              type: "nostalgia",

                                                              /**
                                                               * @memberOf fabric.Image.filters.RemoveWhite.prototype
                                                               * @param {Object} [options] Options object

                                                               /**
                                                               * @method applyTo
                                                               * @param {Object} canvasEl Canvas element to apply filter to
                                                               */
                                                              applyTo: function( canvasEl ) {

                                                                  var context = canvasEl.getContext( '2d' ), imageData = context.getImageData( 0, 0, canvasEl.width,
                                                                                                                                               canvasEl.height ), data = imageData.data, width = canvasEl.width, height = canvasEl.height, r, g, b, a;

                                                                  var layers = [];
                                                                  var processfunc = [];

                                                                  processfunc.push( fabric.imgutil.saturation( 20 ) );
                                                                  processfunc.push( fabric.imgutil.gamma( 1.4 ) );
                                                                  processfunc.push( fabric.imgutil.greyscale() );
                                                                  processfunc.push( fabric.imgutil.contrast( 5 ) );
                                                                  processfunc.push( fabric.imgutil.sepia( 100 ) );
                                                                  processfunc.push( fabric.imgutil.channels( {
                                                                                                                 red: 8,
                                                                                                                 blue: 2,
                                                                                                                 green: 4
                                                                                                             } ) );
                                                                  processfunc.push( fabric.imgutil.gamma( 0.8 ) );
                                                                  processfunc.push( fabric.imgutil.contrast( 5 ) );
                                                                  processfunc.push( fabric.imgutil.exposure( 10 ) );
                                                                  processfunc.push( 0 );
                                                                  layers.push( fabric.imgutil.addLayer( data, width, height ) );
                                                                  var filter0 = [];
                                                                  var layerFilter = [];

                                                                  filter0.push( 'opacity:55' );
                                                                  filter0.push( 'stackBlur:10' );

                                                                  layerFilter.push( [Blender.overlay, filter0] );

                                                                  processfunc.push();
                                                                  var funcLen = processfunc.length;

                                                                  for( var j = 0; j < funcLen; j++ ) {
                                                                      if( typeof processfunc[j] === 'number' ) {
                                                                          fabric.imgutil.applyLayer( layerFilter[processfunc[j]], data, layers[processfunc[j]].pixels, width, height );
                                                                          continue;
                                                                      }

                                                                      for( var i = 0, len = data.length; i < len; i += 4 ) {
                                                                          r = data[i];
                                                                          g = data[i + 1];
                                                                          b = data[i + 2];
                                                                          a = data[i + 3];

                                                                          var rgba = {r: r, g: g, b: b, a: a}
                                                                          rgba = processfunc[j]( rgba );

                                                                          data[i] = rgba.r;
                                                                          data[i + 1] = rgba.g;
                                                                          data[i + 2] = rgba.b;
                                                                          data[i + 3] = rgba.a;

                                                                      }
                                                                  }

                                                                  for( i = 0, len = layers.length; i < len; i++ ) {
                                                                      layers[i].layer = null;
                                                                  }
                                                                  fabric.imgutil.vignette( "50%", 30, data, width, height );
                                                                  context.putImageData( imageData, 0, 0 );
                                                              },

                                                              /**
                                                               * @method toJSON
                                                               * @return {String} json representation of filter
                                                               */
                                                              toJSON: function() {
                                                                  return {
                                                                      type: this.type
                                                                  };
                                                              }

                                                          } );
fabric.Image.filters.nostalgia.fromObject = function( object ) {
    return new fabric.Image.filters.nostalgia( object );
};

fabric.Image.filters.glowingSun = fabric.util.createClass( {

                                                               /**
                                                                * @param {String} type
                                                                */
                                                               type: "glowingSun",

                                                               /**
                                                                * @memberOf fabric.Image.filters.RemoveWhite.prototype
                                                                * @param {Object} [options] Options object

                                                                /**
                                                                * @method applyTo
                                                                * @param {Object} canvasEl Canvas element to apply filter to
                                                                */
                                                               applyTo: function( canvasEl ) {

                                                                   var context = canvasEl.getContext( '2d' ), imageData = context.getImageData( 0, 0, canvasEl.width,
                                                                                                                                                canvasEl.height ), data = imageData.data, width = canvasEl.width, height = canvasEl.height, r, g, b, a;

                                                                   var layers = [];
                                                                   var processfunc = [];
                                                                   var filter0 = [];

                                                                   processfunc.push( fabric.imgutil.brightness( 10 ) );
                                                                   processfunc.push( 0 );
                                                                   filter0.push( 'opacity:80' );
                                                                   filter0.push( fabric.imgutil.gamma( 0.8 ) );
                                                                   filter0.push( fabric.imgutil.contrast( 50 ) );
                                                                   filter0.push( fabric.imgutil.exposure( 10 ) );
                                                                   layers.push( {
                                                                                    layer: fabric.imgutil.addLayer( data, width, height ),
                                                                                    filter: [Blender.multiply, filter0]
                                                                                } );

                                                                   processfunc.push( 1 );
                                                                   var filter1 = [];
                                                                   filter1.push( 'opacity:80' );
                                                                   filter1.push( fabric.imgutil.fillColor( "#f49600" ) );
                                                                   layers.push( {
                                                                                    layer: fabric.imgutil.addLayer( data, width, height ),
                                                                                    filter: [Blender.softLight, filter1]
                                                                                } );

                                                                   processfunc.push( fabric.imgutil.exposure( 20 ) );
                                                                   processfunc.push( fabric.imgutil.gamma( 0.8 ) );
                                                                   processfunc.push( 'vignette:45%:20' );
                                                                   fabric.imgutil.renderFilters( data, width, height, processfunc, layers );
                                                                   context.putImageData( imageData, 0, 0 );
                                                               },

                                                               /**
                                                                * @method toJSON
                                                                * @return {String} json representation of filter
                                                                */
                                                               toJSON: function() {
                                                                   return {
                                                                       type: this.type
                                                                   };
                                                               }

                                                           } );
fabric.Image.filters.glowingSun.fromObject = function( object ) {
    return new fabric.Image.filters.glowingSun( object );
};

fabric.Image.filters.love = fabric.util.createClass( {

                                                         /**
                                                          * @param {String} type
                                                          */
                                                         type: "love",

                                                         /**
                                                          * @memberOf fabric.Image.filters.RemoveWhite.prototype
                                                          * @param {Object} [options] Options object

                                                          /**
                                                          * @method applyTo
                                                          * @param {Object} canvasEl Canvas element to apply filter to
                                                          */
                                                         applyTo: function( canvasEl ) {

                                                             var context = canvasEl.getContext( '2d' ), imageData = context.getImageData( 0, 0, canvasEl.width,
                                                                                                                                          canvasEl.height ), data = imageData.data, width = canvasEl.width, height = canvasEl.height, r, g, b;
                                                             var processfunc = [];

                                                             processfunc.push( fabric.imgutil.brightness( 5 ) );
                                                             processfunc.push( fabric.imgutil.exposure( 8 ) );
                                                             processfunc.push( fabric.imgutil.contrast( 4 ) );
                                                             processfunc.push( fabric.imgutil.colorize( '#c42007', 30 ) );
                                                             processfunc.push( fabric.imgutil.vibrance( 50 ) );
                                                             processfunc.push( fabric.imgutil.gamma( 1.3 ) );

                                                             var funcLen = processfunc.length;
                                                             for( var j = 0; j < funcLen; j++ ) {

                                                                 for( var i = 0, len = data.length; i < len; i += 4 ) {

                                                                     r = data[i];
                                                                     g = data[i + 1];
                                                                     b = data[i + 2];
                                                                     var rgba = {r: r, g: g, b: b}
                                                                     rgba = processfunc[j]( rgba );
                                                                     data[i] = rgba.r;
                                                                     data[i + 1] = rgba.g;
                                                                     data[i + 2] = rgba.b;

                                                                 }
                                                             }
                                                             context.putImageData( imageData, 0, 0 );

                                                         },

                                                         /**
                                                          * @method toJSON
                                                          * @return {String} json representation of filter
                                                          */
                                                         toJSON: function() {
                                                             return {
                                                                 type: this.type
                                                             };
                                                         }



                                                     } );
fabric.Image.filters.love.fromObject = function( object ) {
    return new fabric.Image.filters.love( object );
};

fabric.Image.filters.lomo = fabric.util.createClass( {

                                                         /**
                                                          * @param {String} type
                                                          */
                                                         type: "lomo",

                                                         /**
                                                          * @memberOf fabric.Image.filters.RemoveWhite.prototype
                                                          * @param {Object} [options] Options object

                                                          /**
                                                          * @method applyTo
                                                          * @param {Object} canvasEl Canvas element to apply filter to
                                                          */
                                                         applyTo: function( canvasEl ) {

                                                             var context = canvasEl.getContext( '2d' ), imageData = context.getImageData( 0, 0, canvasEl.width,
                                                                                                                                          canvasEl.height ), data = imageData.data, width = canvasEl.width, height = canvasEl.height, r, g, b;
                                                             var processfunc = [];

                                                             processfunc.push( fabric.imgutil.brightness( 15 ) );
                                                             processfunc.push( fabric.imgutil.exposure( 15 ) );
                                                             processfunc.push( fabric.imgutil.curves( 'rgb', [0, 0], [200, 0], [155, 255], [255, 255] ) );
                                                             processfunc.push( fabric.imgutil.saturation( -20 ) );
                                                             processfunc.push( fabric.imgutil.gamma( 1.8 ) );
                                                             processfunc.push( 'vignette:50%:60' );
                                                             processfunc.push( fabric.imgutil.brightness( 5 ) );
                                                             var funcLen = processfunc.length;
                                                             for( var j = 0; j < funcLen; j++ ) {
                                                                 if( typeof processfunc[j] === 'string' ) {
                                                                     var filter = processfunc[j].split( ':' );
                                                                     if( filter[0] == "vignette" ) {
                                                                         fabric.imgutil.vignette( filter[1], filter[2], data, width, height );
                                                                     }
                                                                     continue;
                                                                 }

                                                                 for( var i = 0, len = data.length; i < len; i += 4 ) {

                                                                     r = data[i];
                                                                     g = data[i + 1];
                                                                     b = data[i + 2];
                                                                     var rgba = {r: r, g: g, b: b}
                                                                     rgba = processfunc[j]( rgba );
                                                                     data[i] = rgba.r;
                                                                     data[i + 1] = rgba.g;
                                                                     data[i + 2] = rgba.b;

                                                                 }
                                                             }
                                                             context.putImageData( imageData, 0, 0 );
                                                         },

                                                         /**
                                                          * @method toJSON
                                                          * @return {String} json representation of filter
                                                          */
                                                         toJSON: function() {
                                                             return {
                                                                 type: this.type
                                                             };
                                                         }



                                                     } );
fabric.Image.filters.lomo.fromObject = function( object ) {
    return new fabric.Image.filters.lomo( object );
};

fabric.Image.filters.jarques = fabric.util.createClass( {

                                                            /**
                                                             * @param {String} type
                                                             */
                                                            type: "jarques",

                                                            /**
                                                             * @memberOf fabric.Image.filters.RemoveWhite.prototype
                                                             * @param {Object} [options] Options object

                                                             /**
                                                             * @method applyTo
                                                             * @param {Object} canvasEl Canvas element to apply filter to
                                                             */
                                                            applyTo: function( canvasEl ) {

                                                                var context = canvasEl.getContext( '2d' ), imageData = context.getImageData( 0, 0, canvasEl.width,
                                                                                                                                             canvasEl.height ), data = imageData.data, width = canvasEl.width, height = canvasEl.height;
                                                                //            r, g, b;
                                                                var processfunc = [];

                                                                processfunc.push( fabric.imgutil.saturation( -35 ) );
                                                                processfunc.push( fabric.imgutil.curves( 'b', [20, 0], [90, 120], [186, 144], [255, 230] ) );
                                                                processfunc.push( fabric.imgutil.curves( 'r', [0, 0], [144, 90], [138, 120], [255, 255] ) );
                                                                processfunc.push( fabric.imgutil.curves( 'g', [10, 0], [115, 105], [148, 100], [255, 248] ) );
                                                                processfunc.push( fabric.imgutil.curves( 'rgb', [0, 0], [120, 100], [128, 140], [255, 255] ) );
                                                                processfunc.push( 'sharpen:20' );

                                                                fabric.imgutil.renderFilters( data, width, height, processfunc );
                                                                context.putImageData( imageData, 0, 0 );
                                                            },

                                                            /**
                                                             * @method toJSON
                                                             * @return {String} json representation of filter
                                                             */
                                                            toJSON: function() {
                                                                return {
                                                                    type: this.type
                                                                };
                                                            }



                                                        } );
fabric.Image.filters.jarques.fromObject = function( object ) {
    return new fabric.Image.filters.jarques( object );
};

fabric.Image.filters.grungy = fabric.util.createClass( {

                                                           /**
                                                            * @param {String} type
                                                            */
                                                           type: "grungy",

                                                           /**
                                                            * @memberOf fabric.Image.filters.RemoveWhite.prototype
                                                            * @param {Object} [options] Options object

                                                            /**
                                                            * @method applyTo
                                                            * @param {Object} canvasEl Canvas element to apply filter to
                                                            */
                                                           applyTo: function( canvasEl ) {
                                                               var context = canvasEl.getContext( '2d' ), imageData = context.getImageData( 0, 0, canvasEl.width,
                                                                                                                                            canvasEl.height ), data = imageData.data, width = canvasEl.width, height = canvasEl.height;
                                                               //            r, g, b;
                                                               var processfunc = [];

                                                               processfunc.push( fabric.imgutil.gamma( 1.5 ) );
                                                               processfunc.push( fabric.imgutil.clip( 25 ) );
                                                               processfunc.push( fabric.imgutil.saturation( -60 ) );
                                                               processfunc.push( fabric.imgutil.contrast( 5 ) );
                                                               processfunc.push( fabric.imgutil.noise( 5 ) );
                                                               processfunc.push( 'vignette:50%:30' );

                                                               fabric.imgutil.renderFilters( data, width, height, processfunc );
                                                               context.putImageData( imageData, 0, 0 );
                                                           },

                                                           /**
                                                            * @method toJSON
                                                            * @return {String} json representation of filter
                                                            */
                                                           toJSON: function() {
                                                               return {
                                                                   type: this.type
                                                               };
                                                           }



                                                       } );
fabric.Image.filters.grungy.fromObject = function( object ) {
    return new fabric.Image.filters.grungy( object );
};

fabric.Image.filters.herMajesty = fabric.util.createClass( {

                                                               /**
                                                                * @param {String} type
                                                                */
                                                               type: "herMajesty",

                                                               /**
                                                                * @memberOf fabric.Image.filters.RemoveWhite.prototype
                                                                * @param {Object} [options] Options object

                                                                /**
                                                                * @method applyTo
                                                                * @param {Object} canvasEl Canvas element to apply filter to
                                                                */
                                                               applyTo: function( canvasEl ) {

                                                                   var context = canvasEl.getContext( '2d' ), imageData = context.getImageData( 0, 0, canvasEl.width,
                                                                                                                                                canvasEl.height ), data = imageData.data, width = canvasEl.width, height = canvasEl.height;

                                                                   var processfunc = [];
                                                                   var layers = [];
                                                                   var filter0 = [];

                                                                   processfunc.push( fabric.imgutil.brightness( 40 ) );
                                                                   processfunc.push( fabric.imgutil.colorize( "#ea1c5d", 10 ) );
                                                                   processfunc.push( fabric.imgutil.curves( 'b', [0, 10], [128, 180], [190, 190], [255, 255] ) );
                                                                   processfunc.push( 0 );

                                                                   filter0.push( 'opacity:50' );
                                                                   filter0.push( fabric.imgutil.gamma( 0.7 ) );
                                                                   // layer 0-1
                                                                   filter0.push( 1 );
                                                                   layers.push( {
                                                                                    layer: fabric.imgutil.addLayer( data, width, height ),
                                                                                    filter: [Blender.overlay, filter0]
                                                                                } );
                                                                   var filter1 = [];
                                                                   filter1.push( 'opacity:60' );
                                                                   filter1.push( fabric.imgutil.fillColor( "#ea1c5d" ) );
                                                                   layers.push( {
                                                                                    layer: fabric.imgutil.addLayer( data, width, height ),
                                                                                    filter: [Blender.normal, filter1]
                                                                                } );

                                                                   // layer 1
                                                                   processfunc.push( 2 );
                                                                   var filter2 = [];
                                                                   filter2.push( 'opacity:60' );
                                                                   filter2.push( fabric.imgutil.saturation( 50 ) );
                                                                   filter2.push( fabric.imgutil.hue( 90 ) );
                                                                   filter2.push( fabric.imgutil.contrast( 10 ) );

                                                                   layers.push( {
                                                                                    layer: fabric.imgutil.addLayer( data, width, height ),
                                                                                    filter: [Blender.multiply, filter2]
                                                                                } );

                                                                   processfunc.push( fabric.imgutil.gamma( 1.4 ) );
                                                                   processfunc.push( fabric.imgutil.vibrance( -30 ) );
                                                                   //
                                                                   //        // layer 2
                                                                   processfunc.push( 3 );
                                                                   var filter3 = [];
                                                                   filter3.push( 'opacity:10' );
                                                                   filter3.push( fabric.imgutil.fillColor( '#e5f0ff' ) );

                                                                   layers.push( {
                                                                                    layer: fabric.imgutil.addLayer( data, width, height ),
                                                                                    filter: [Blender.normal, filter3]
                                                                                } );

                                                                   fabric.imgutil.renderFilters( data, width, height, processfunc, layers );
                                                                   context.putImageData( imageData, 0, 0 );
                                                               },

                                                               /**
                                                                * @method toJSON
                                                                * @return {String} json representation of filter
                                                                */
                                                               toJSON: function() {
                                                                   return {
                                                                       type: this.type
                                                                   };
                                                               }



                                                           } );
fabric.Image.filters.herMajesty.fromObject = function( object ) {
    return new fabric.Image.filters.herMajesty( object );
};

fabric.Image.filters.hemingway = fabric.util.createClass( {

                                                              /**
                                                               * @param {String} type
                                                               */
                                                              type: "hemingway",

                                                              /**
                                                               * @memberOf fabric.Image.filters.RemoveWhite.prototype
                                                               * @param {Object} [options] Options object

                                                               /**
                                                               * @method applyTo
                                                               * @param {Object} canvasEl Canvas element to apply filter to
                                                               */
                                                              applyTo: function( canvasEl ) {

                                                                  var context = canvasEl.getContext( '2d' ), imageData = context.getImageData( 0, 0, canvasEl.width,
                                                                                                                                               canvasEl.height ), data = imageData.data, width = canvasEl.width, height = canvasEl.height;

                                                                  var processfunc = [];
                                                                  var layers = [];
                                                                  var filter0 = [];

                                                                  processfunc.push( fabric.imgutil.greyscale() );
                                                                  processfunc.push( fabric.imgutil.contrast( 10 ) );
                                                                  processfunc.push( fabric.imgutil.gamma( 0.9 ) );
                                                                  processfunc.push( 0 );

                                                                  filter0.push( 'opacity:40' );
                                                                  filter0.push( fabric.imgutil.exposure( 15 ) );
                                                                  filter0.push( fabric.imgutil.contrast( 15 ) );
                                                                  filter0.push( fabric.imgutil.channels( {
                                                                                                             green: 10,
                                                                                                             red: 5
                                                                                                         } ) );

                                                                  layers.push( {
                                                                                   layer: fabric.imgutil.addLayer( data, width, height ),
                                                                                   filter: [Blender.multiply, filter0]
                                                                               } );

                                                                  processfunc.push( fabric.imgutil.sepia( 30 ) );
                                                                  processfunc.push( fabric.imgutil.curves( 'rgb', [0, 10], [120, 90], [180, 200], [235, 255] ) );
                                                                  processfunc.push( fabric.imgutil.channels( {
                                                                                                                 red: 5,
                                                                                                                 green: -2
                                                                                                             } ) );
                                                                  processfunc.push( fabric.imgutil.exposure( 15 ) );
                                                                  fabric.imgutil.renderFilters( data, width, height, processfunc, layers );
                                                                  context.putImageData( imageData, 0, 0 );
                                                              },

                                                              /**
                                                               * @method toJSON
                                                               * @return {String} json representation of filter
                                                               */
                                                              toJSON: function() {
                                                                  return {
                                                                      type: this.type
                                                                  };
                                                              }



                                                          } );
fabric.Image.filters.hemingway.fromObject = function( object ) {
    return new fabric.Image.filters.hemingway( object );
};

fabric.Image.filters.concentrate = fabric.util.createClass( {

                                                                /**
                                                                 * @param {String} type
                                                                 */
                                                                type: "concentrate",

                                                                /**
                                                                 * @memberOf fabric.Image.filters.RemoveWhite.prototype
                                                                 * @param {Object} [options] Options object

                                                                 /**
                                                                 * @method applyTo
                                                                 * @param {Object} canvasEl Canvas element to apply filter to
                                                                 */
                                                                applyTo: function( canvasEl ) {

                                                                    var context = canvasEl.getContext( '2d' ), imageData = context.getImageData( 0, 0, canvasEl.width,
                                                                                                                                                 canvasEl.height ), data = imageData.data, width = canvasEl.width, height = canvasEl.height;

                                                                    var processfunc = [];
                                                                    var layers = [];
                                                                    var filter0 = [];

                                                                    processfunc.push( 'sharpen:40' );
                                                                    processfunc.push( fabric.imgutil.saturation( -50 ) );
                                                                    processfunc.push( fabric.imgutil.channels( {
                                                                                                                   red: 3
                                                                                                               } ) );
                                                                    processfunc.push( 0 );

                                                                    filter0.push( 'opacity:80' );
                                                                    filter0.push( 'sharpen:5' );
                                                                    filter0.push( fabric.imgutil.contrast( 50 ) );
                                                                    filter0.push( fabric.imgutil.exposure( 10 ) );
                                                                    filter0.push( fabric.imgutil.channels( {
                                                                                                               blue: 5
                                                                                                           } ) );

                                                                    layers.push( {
                                                                                     layer: fabric.imgutil.addLayer( data, width, height ),
                                                                                     filter: [Blender.multiply, filter0]
                                                                                 } );

                                                                    processfunc.push( fabric.imgutil.brightness( 10 ) );
                                                                    fabric.imgutil.renderFilters( data, width, height, processfunc, layers );
                                                                    context.putImageData( imageData, 0, 0 );
                                                                },

                                                                /**
                                                                 * @method toJSON
                                                                 * @return {String} json representation of filter
                                                                 */
                                                                toJSON: function() {
                                                                    return {
                                                                        type: this.type
                                                                    };
                                                                }



                                                            } );
fabric.Image.filters.concentrate.fromObject = function( object ) {
    return new fabric.Image.filters.concentrate( object );
};

fabric.Image.filters.hazyDays = fabric.util.createClass( {

                                                             /**
                                                              * @param {String} type
                                                              */
                                                             type: "hazyDays",

                                                             /**
                                                              * @memberOf fabric.Image.filters.RemoveWhite.prototype
                                                              * @param {Object} [options] Options object

                                                              /**
                                                              * @method applyTo
                                                              * @param {Object} canvasEl Canvas element to apply filter to
                                                              */
                                                             applyTo: function( canvasEl ) {

                                                                 var context = canvasEl.getContext( '2d' ), imageData = context.getImageData( 0, 0, canvasEl.width,
                                                                                                                                              canvasEl.height ), data = imageData.data, width = canvasEl.width, height = canvasEl.height;

                                                                 var processfunc = [];
                                                                 var layers = [];
                                                                 var filter0 = [];

                                                                 processfunc.push( fabric.imgutil.gamma( 1.2 ) );

                                                                 processfunc.push( 0 );
                                                                 filter0.push( 'opacity:60' );
                                                                 filter0.push( fabric.imgutil.channels( {
                                                                                                            red: 5
                                                                                                        } ) );
                                                                 filter0.push( 'stackBlur:15' );
                                                                 layers.push( {
                                                                                  layer: fabric.imgutil.addLayer( data, width, height ),
                                                                                  filter: [Blender.overlay, filter0]
                                                                              } );

                                                                 var filter1 = [];
                                                                 processfunc.push( 1 );
                                                                 filter1.push( 'opacity:40' );
                                                                 filter1.push( fabric.imgutil.fillColor( "#6899ba" ) );

                                                                 layers.push( {
                                                                                  layer: fabric.imgutil.addLayer( data, width, height ),
                                                                                  filter: [Blender.addition, filter1]
                                                                              } );

                                                                 var filter2 = [];
                                                                 processfunc.push( 2 );
                                                                 filter2.push( 'opacity:35' );
                                                                 filter2.push( fabric.imgutil.brightness( 40 ) );
                                                                 filter2.push( fabric.imgutil.vibrance( 40 ) );
                                                                 filter2.push( fabric.imgutil.exposure( 30 ) );
                                                                 filter2.push( fabric.imgutil.contrast( 15 ) );
                                                                 filter2.push( fabric.imgutil.curves( 'r', [0, 40], [128, 128], [128, 128], [255, 215] ) );
                                                                 filter2.push( fabric.imgutil.curves( 'g', [0, 40], [128, 128], [128, 128], [255, 215] ) );
                                                                 filter2.push( fabric.imgutil.curves( 'b', [0, 40], [128, 128], [128, 128], [255, 215] ) );
                                                                 filter2.push( 'stackBlur:5' );
                                                                 layers.push( {
                                                                                  layer: fabric.imgutil.addLayer( data, width, height ),
                                                                                  filter: [Blender.multiply, filter2]
                                                                              } );

                                                                 processfunc.push( fabric.imgutil.curves( 'r', [20, 0], [128, 158], [128, 128], [235, 255] ) );
                                                                 processfunc.push( fabric.imgutil.curves( 'g', [20, 0], [128, 128], [128, 128], [235, 255] ) );
                                                                 processfunc.push( fabric.imgutil.curves( 'b', [20, 0], [128, 108], [128, 128], [235, 255] ) );
                                                                 processfunc.push( 'vignette:45%:20' );

                                                                 fabric.imgutil.renderFilters( data, width, height, processfunc, layers );
                                                                 context.putImageData( imageData, 0, 0 );
                                                             },

                                                             /**
                                                              * @method toJSON
                                                              * @return {String} json representation of filter
                                                              */
                                                             toJSON: function() {
                                                                 return {
                                                                     type: this.type
                                                                 };
                                                             }



                                                         } );
fabric.Image.filters.hazyDays.fromObject = function( object ) {
    return new fabric.Image.filters.hazyDays( object );
};

fabric.Image.filters.crossProcess = fabric.util.createClass( {

                                                                 /**
                                                                  * @param {String} type
                                                                  */
                                                                 type: "crossProcess",

                                                                 /**
                                                                  * @memberOf fabric.Image.filters.RemoveWhite.prototype
                                                                  * @param {Object} [options] Options object

                                                                  /**
                                                                  * @method applyTo
                                                                  * @param {Object} canvasEl Canvas element to apply filter to
                                                                  */
                                                                 applyTo: function( canvasEl ) {

                                                                     var context = canvasEl.getContext( '2d' ), imageData = context.getImageData( 0, 0, canvasEl.width,
                                                                                                                                                  canvasEl.height ), data = imageData.data, width = canvasEl.width, height = canvasEl.height;

                                                                     var processfunc = [];

                                                                     processfunc.push( fabric.imgutil.exposure( 5 ) );
                                                                     processfunc.push( fabric.imgutil.colorize( "#e87b22", 4 ) );
                                                                     processfunc.push( fabric.imgutil.sepia( 20 ) );
                                                                     processfunc.push( fabric.imgutil.channels( {   blue: 8, red: 3   } ) );
                                                                     processfunc.push( fabric.imgutil.curves( 'b', [0, 0], [100, 150], [180, 180], [255, 255] ) );
                                                                     processfunc.push( fabric.imgutil.contrast( 15 ) );
                                                                     processfunc.push( fabric.imgutil.vibrance( 75 ) );
                                                                     processfunc.push( fabric.imgutil.gamma( 1.6 ) );

                                                                     fabric.imgutil.renderFilters( data, width, height, processfunc );
                                                                     context.putImageData( imageData, 0, 0 );
                                                                 },

                                                                 /**
                                                                  * @method toJSON
                                                                  * @return {String} json representation of filter
                                                                  */
                                                                 toJSON: function() {
                                                                     return {
                                                                         type: this.type
                                                                     };
                                                                 }



                                                             } );
fabric.Image.filters.crossProcess.fromObject = function( object ) {
    return new fabric.Image.filters.crossProcess( object );
};

fabric.Image.filters.clarity = fabric.util.createClass( {

                                                            /**
                                                             * @param {String} type
                                                             */
                                                            type: "clarity",

                                                            /**
                                                             * @memberOf fabric.Image.filters.RemoveWhite.prototype
                                                             * @param {Object} [options] Options object

                                                             /**
                                                             * @method applyTo
                                                             * @param {Object} canvasEl Canvas element to apply filter to
                                                             */
                                                            applyTo: function( canvasEl ) {

                                                                var context = canvasEl.getContext( '2d' ), imageData = context.getImageData( 0, 0, canvasEl.width,
                                                                                                                                             canvasEl.height ), data = imageData.data, width = canvasEl.width, height = canvasEl.height;

                                                                var processfunc = [];

                                                                processfunc.push( fabric.imgutil.vibrance( 20 ) );
                                                                processfunc.push( fabric.imgutil.curves( 'rgb', [5, 0], [130, 150], [190, 220], [250, 255] ) );
                                                                processfunc.push( 'sharpen:15' );
                                                                processfunc.push( 'vignette:45%:20' );
                                                                //        processfunc.push(fabric.imgutil.greyscale());
                                                                //        processfunc.push(fabric.imgutil.contrast(4));

                                                                fabric.imgutil.renderFilters( data, width, height, processfunc );
                                                                context.putImageData( imageData, 0, 0 );
                                                            },

                                                            /**
                                                             * @method toJSON
                                                             * @return {String} json representation of filter
                                                             */
                                                            toJSON: function() {
                                                                return {
                                                                    type: this.type
                                                                };
                                                            }



                                                        } );
fabric.Image.filters.clarity.fromObject = function( object ) {
    return new fabric.Image.filters.clarity( object );
};

/**
 * END ADDITIONAL FILTERS
 **/

var f = fabric.Image.filters;

