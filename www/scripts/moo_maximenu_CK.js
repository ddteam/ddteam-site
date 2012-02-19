if (typeof(MooTools) != 'undefined'){

    var DropdownMaxiMenu = new Class({
        Implements: Options,
        options: {    //options par d�faut si aucune option utilisateur n'est renseign�e
			
            mooTransition : 'Bounce',
            mooEase : 'easeOut',
            mooDuree : 500,
            useOpacity : '0',
            menuID : 'maximenuCK',
            testoverflow : '1',
            orientation : '0',
            dureeOut : 500
        },
			
        initialize: function(element,options) {
			
            this.setOptions(options); //enregistre les options utilisateur

            var maduree = this.options.mooDuree;
            var matransition = this.options.mooTransition;
            var monease = this.options.mooEase;
            var useopacity = this.options.useOpacity;
            var dureeout = this.options.dureeOut;
            var menuID = this.options.menuID;
            var testoverflow = this.options.testoverflow;
            var orientation = this.options.orientation;

            var els = element.getElements('li.maximenuCK');

            els.each(function(el) {
										
                if (el.getElement('div.floatCK') != null) {
                    el.conteneur = el.getElement('div.floatCK');
						
                    el.conteneurul = el.getElements('div.floatCK ul');
                    el.conteneurul.setStyle('position','static');
						
                    el.conteneur.mh = el.conteneur.clientHeight;
                    el.conteneur.mw = el.conteneur.clientWidth;
                    el.duree = maduree;
                    el.transition = matransition;
                    el.ease = monease;
                    el.useopacity = useopacity;
                    el.orientation = orientation;
                    el.zindex = el.getStyle('z-index');
                    el.createFxMaxiCK();

                    el.addEvent('mouseover',function() {
                        
                        if (testoverflow == '1') this.testOverflowMaxiCK(menuID);
                        this.showMaxiCK();

                    });
                    el.addEvent('mouseleave',function() {
							
                        this.hideMaxiCK(dureeout); //si timeout, probl�me de d�roulement
							
                    });
                }
            });
        }
			
    });

    if (MooTools.version > '1.12' ) Element.extend = Element.implement;

       
    Element.extend({

        testOverflowMaxiCK: function(menuID) {
            var limite = $(menuID).offsetWidth + $(menuID).getLeft();


            if (this.hasClass('parent')) {
                var largeur = this.conteneur.mw + 180;
                if (this.hasClass('level0')) largeur = this.conteneur.mw;

                var positionx = this.getLeft() + largeur;

                if (positionx > limite) {
                    this.getElement('div.floatCK').addClass('fixRight');
                    this.setStyle('z-index','15000');
                }
				
            }

        },

               
        createFxMaxiCK: function() {
			
            var myTransition = new Fx.Transition(Fx.Transitions[this.transition][this.ease]);
            if (this.hasClass('level0') && this.orientation != '1')
            {
                this.maxiFxCK = new Fx.Tween(this.conteneur, {
                    property:'height',
                    duration:this.duree,
                    transition: myTransition});
            } else {
                this.maxiFxCK = new Fx.Tween(this.conteneur, {
                    property:'width',
                    duration:this.duree,
                    transition: myTransition});
            }

            if (this.useopacity == '1') {
                this.maxiOpacityCK = new Fx.Style(this.conteneur, 'opacity', {
                    duration:this.duree
                });
                this.maxiOpacityCK.set(0);
            }
            

            this.maxiFxCK.set(0);
            
            this.conteneur.setStyle('left', '-999em');
				
            animComp = function(){
                if (this.status == 'hide')
                {
                    this.conteneur.setStyle('left', '-999em');
                    this.hidding = 0;
                    this.setStyle('z-index',this.zindex);
                }
                this.showing = 0;
                this.conteneur.setStyle('overflow', '');
					
            }
            this.maxiFxCK.addEvent ('onComplete', animComp.bind(this));

        },
			
        showMaxiCK: function() {
            clearTimeout (this.timeout);
            this.addClass('sfhover');
            this.status = 'show';
            this.animMaxiCK();
        },
			
        hideMaxiCK: function(timeout) {
            this.status = 'hide';
            this.removeClass('sfhover');
            clearTimeout (this.timeout);
            if (timeout)
            {
                this.timeout = setTimeout (this.animMaxiCK.bind(this), timeout);
            }else{
                this.animMaxiCK();
            }
        },

        animMaxiCK: function() {

            if ((this.status == 'hide' && this.conteneur.style.left != 'auto') || (this.status == 'show' && this.conteneur.style.left == 'auto' && !this.hidding) ) return;
					
            this.conteneur.setStyle('overflow', 'hidden');
            if (this.status == 'show') {
                this.hidding = 0;
            }
            if (this.status == 'hide')
            {
                this.hidding = 1;
                this.showing = 0;
                this.maxiFxCK.cancel();
					
                if (this.hasClass('level0') && this.orientation != '1') {
                    this.maxiFxCK.start(this.conteneur.offsetHeight,0);
                } else {
                    this.maxiFxCK.start(this.conteneur.offsetWidth,0);
                }
                if (this.useopacity == '1') {
                    this.maxiOpacityCK.cancel();
                    this.maxiOpacityCK.start(1,0);
                }
                

            } else {
                this.showing = 1;
                this.conteneur.setStyle('left', 'auto');
                this.maxiFxCK.cancel();
                if (this.hasClass('level0') && this.orientation != '1') {
                    this.maxiFxCK.start(this.conteneur.offsetHeight,this.conteneur.mh);
                } else {
                    this.maxiFxCK.start(this.conteneur.offsetWidth,this.conteneur.mw);
                }
                if (this.useopacity == '1') {
                    this.maxiOpacityCK.cancel();
                    this.maxiOpacityCK.start(0,1);
                }
                
            }
				

        }
    });

    DropdownMaxiMenu.implement(new Options); //ajoute les options utilisateur � la class

		
/*Window.onDomReady(function() {new DropdownMenu($E('ul.maximenuCK'),{
                  //mooTransition : 'Quad',
			               //mooTransition : 'Cubic',
			               //mooTransition : 'Quart',
			               //mooTransition : 'Quint',
			               //mooTransition : 'Pow',
			               //mooTransition : 'Expo',
			               //mooTransition : 'Circ',
			               mooTransition : 'Sine',
			               //mooTransition : 'Back',
			               //mooTransition : 'Bounce',
			               //mooTransition : 'Elastic',

			               mooEase : 'easeIn',
                                       //mooEase : 'easeOut',
                                       //mooEase : 'easeInOut',
                                       
                                       mooDuree : 500
                                       })
                                       });*/

}