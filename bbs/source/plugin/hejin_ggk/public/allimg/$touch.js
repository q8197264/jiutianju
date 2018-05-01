(function( gmu, $, undefined ) {
    
    var map = {
            touchstart: '_onStart',
            touchmove: '_onMove',
            touchend: '_onEnd',
            touchcancel: '_onEnd',
            click: '_onClick'
        },

        isScrolling,
        start,
        delta,
        moved;

    $.extend( gmu.Slider.options, {

        stopPropagation: false,

        disableScroll: false
    } );

    gmu.Slider.register( 'touch', {
        _init: function() {
            var me = this,
                $el = me.getEl();

            me._handler = function( e ) {
                me._options.stopPropagation && e.stopPropagation();
                return map[ e.type ] && me[ map[ e.type ] ].call( me, e );
            };

            me.on( 'ready', function() {

                $el.on( 'touchstart' + me.eventNs, me._handler );
                
                me._container.on( 'click' + me.eventNs, me._handler );
            } );
        },

        _onClick: function() {
            return !moved;
        },

        _onStart: function( e ) {
                
            if ( e.touches.length > 1 ) {
                return false;
            }

            var me = this,
                touche = e.touches[ 0 ],
                opts = me._options,
                eventNs = me.eventNs,
                num;

            start = {
                x: touche.pageX,
                y: touche.pageY,
                time: +new Date()
            };

            delta = {};
            moved = false;
            isScrolling = undefined;

            num = opts.viewNum || 1;
            me._move( opts.loop ? me._circle( me.index - num ) :
                    me.index - num, -me.width, 0, true );
            me._move( opts.loop ? me._circle( me.index + num ) :
                    me.index + num, me.width, 0, true );

            me.$el.on( 'touchmove' + eventNs + ' touchend' + eventNs +
                    ' touchcancel' + eventNs, me._handler );
        },

        _onMove: function( e ) {

            if ( e.touches.length > 1 || e.scale &&
                    e.scale !== 1 ) {
                return false;
            }

            var opts = this._options,
                viewNum = opts.viewNum || 1,
                touche = e.touches[ 0 ],
                index = this.index,
                i,
                len,
                pos,
                slidePos;

            opts.disableScroll && e.preventDefault();

            delta.x = touche.pageX - start.x;
            delta.y = touche.pageY - start.y;

            if ( typeof isScrolling === 'undefined' ) {
                isScrolling = Math.abs( delta.x ) <
                        Math.abs( delta.y );
            }

            if ( !isScrolling ) {
                e.preventDefault();

                if ( !opts.loop ) {

                    delta.x /= (!index && delta.x > 0 ||

                            index === this._items.length - 1 && 
                            delta.x < 0) ?

                            (Math.abs( delta.x ) / this.width + 1) : 1;
                }

                slidePos = this._slidePos;

                for ( i = index - viewNum, len = index + 2 * viewNum;
                        i < len; i++ ) {

                    pos = opts.loop ? this._circle( i ) : i;
                    this._translate( pos, delta.x + slidePos[ pos ], 0 );
                }

                moved = true;
            }
        },

        _onEnd: function() {

            this.$el.off( 'touchmove' + this.eventNs + ' touchend' +
                    this.eventNs + ' touchcancel' + this.eventNs,
                    this._handler );

            if ( !moved ) {
                return;
            }

            var me = this,
                opts = me._options,
                viewNum = opts.viewNum || 1,
                index = me.index,
                slidePos = me._slidePos,
                duration = +new Date() - start.time,
                absDeltaX = Math.abs( delta.x ),

                isPastBounds = !opts.loop && (!index && delta.x > 0 ||
                    index === slidePos.length - viewNum && delta.x < 0),

                dir = delta.x > 0 ? 1 : -1,
                speed,
                diff,
                i,
                len,
                pos;

            if ( duration < 250 ) {

                speed = absDeltaX / duration;
                diff = Math.min( Math.round( speed * viewNum * 1.2 ),
                        viewNum );
            } else {
                diff = Math.round( absDeltaX / (me.perWidth || me.width) );
            }
            
            if ( diff && !isPastBounds ) {
                me._slide( index, diff, dir, me.width, opts.speed,
                        opts, true );
                
                if ( viewNum > 1 && duration >= 250 &&
                        Math.ceil( absDeltaX / me.perWidth ) !== diff ) {

                    me.index < index ? me._move( me.index - 1, -me.perWidth,
                            opts.speed ) : me._move( me.index + viewNum,
                            me.width, opts.speed );
                }
            } else {
                
                for ( i = index - viewNum, len = index + 2 * viewNum;
                    i < len; i++ ) {

                    pos = opts.loop ? me._circle( i ) : i;
                    me._translate( pos, slidePos[ pos ], 
                            opts.speed );
                }
            }
        }
    } );
})( gmu, gmu.$ );