'use strict';

/**
 * qualityselection
 *
 * displays available sources for MPEG Dash Streaming
 */

// If plugin needs translations, put here English one in this format:
// mejs.i18n.en["mejs.id1"] = "String 1";
// mejs.i18n.en["mejs.id2"] = "String 2";

// Feature configuration
Object.assign(mejs.MepDefaults, {
    // Any variable that can be configured by the end user belongs here.
    // Make sure is unique by checking API and Configuration file.
    // Add comments about the nature of each of these variables.
	defaultQuality: 'auto'
});

Object.assign(MediaElementPlayer.prototype, {
	

    // Public variables (also documented according to JSDoc specifications)

    /**
     * Feature constructor.
     *
     * Always has to be prefixed with `build` and the name that will be used in MepDefaults.features list
     * @param {MediaElementPlayer} player
     * @param {HTMLElement} controls
     * @param {HTMLElement} layers
     * @param {HTMLElement} media
     */
    buildqualityselection (player, controls, layers, media) {	
        // This allows us to access options and other useful elements already set.
        // Adding variables to the object is a good idea if you plan to reuse
        // those variables in further operations.
        const
			t = this,			
			sources = []			
		;
		var value;
		// add to list

		player.qualityselectionButton = document.createElement('div');
        player.qualityselectionButton.className = `${t.options.classPrefix}button ${t.options.classPrefix}qualityselection-button`;
        player.qualityselectionButton.innerHTML =
            `<button type="button" role="button" aria-haspopup="true" aria-owns="${t.id}" tabindex="0"></button>` +
            `<div class="${t.options.classPrefix}qualityselection-selector ${t.options.classPrefix}offscreen" role="menu" aria-expanded="false" aria-hidden="true"><ul></ul></div>`;

		t.addControlElement(player.qualityselectionButton, 'qualityselection');

		if (media.dashPlayer) {
			media.dashPlayer.on(dashjs.MediaPlayer.events.STREAM_INITIALIZED, function() {
				value = player.getSourcesDash(sources, media.dashPlayer);
				if (value === -1) return;
				player.addHoverAndFocus();
				player.addClick(media, t.options.classPrefix);

				media.dashPlayer.on(dashjs.MediaPlayer.events.QUALITY_CHANGE_REQUESTED, function() {
					player.updateQualityButton(media.dashPlayer.getQualityFor('video'), t.options.classPrefix);
				}, t);
			}, t);
		} else if(media.hlsPlayer) {
			media.hlsPlayer.on(Hls.Events.MANIFEST_PARSED, function() {
				value = player.getSourcesHls(sources, media.hlsPlayer);
				if (value === -1) return;
				player.addHoverAndFocus();				
				player.addClick(media, t.options.classPrefix);

				media.hlsPlayer.on(Hls.Events.LEVEL_SWITCHED, function(event, data) {										
					player.updateQualityButton(data.level, t.options.classPrefix);
				});
			});
		}
	},

	getSourcesDash (sources, dashPlayer) {
		const t = this;
		for (let i = 0, total = dashPlayer.getBitrateInfoListFor('video').length; i < total; i++) {
            const s = dashPlayer.getBitrateInfoListFor('video')[i];
		    sources.unshift(s);
		}

		if (sources.length <= 1) {
			return -1;
		}		
                
        for (let i = 0, total = sources.length; i < total; i++) {
			const src = sources[i];
			if (src.mediaType === "video") {
				if (src.qualityIndex === dashPlayer.getQualityFor("video") && !dashPlayer.getAutoSwitchQualityFor("video")) var isCurrent = true;
				t.addQualityButton(src.height + "p", src.qualityIndex, isCurrent);				
			}
		}
		t.addQualityButton("Automatisch", "auto", dashPlayer.getAutoSwitchQualityFor("video"));
		return 0;
	},

	getSourcesHls (sources, hlsPlayer) {
		const t = this;
		var isCurrent = false;

		for (let i = 0, total = hlsPlayer.levels.length; i < total; i++) {
            const s = hlsPlayer.levels[i];
		    sources.unshift(s);
		}

		if (sources.length <= 1) {
			return -1;
		}		
                
        for (let i = 0, total = sources.length; i < total; i++) {
			const src = sources[i];
			if (i === hlsPlayer.firstLevel && !hlsPlayer.autoLevelEnabled) isCurrent = true;
			t.addQualityButton(src.height + "p", sources.length - (1 + i), isCurrent);
		}
		t.addQualityButton("Automatisch", "auto", hlsPlayer.autoLevelEnabled);
		return 0;
	},

	addHoverAndFocus () {
		const t = this;
		let hoverTimeout;
		// hover
		t.qualityselectionButton.addEventListener('mouseover', () => {
			clearTimeout(hoverTimeout);
			t.showQualitySelector();
		});
		t.qualityselectionButton.addEventListener('mouseout', () => {
			hoverTimeout = setTimeout(() => {
				t.hideQualitySelector();
			}, 0);
		});

		// close menu when tabbing away
		t.qualityselectionButton.addEventListener('focusout', mejs.Utils.debounce(() => {
			// Safari triggers focusout multiple times
			// Firefox does NOT support e.relatedTarget to see which element
			// just lost focus, so wait to find the next focused element
			setTimeout(() => {
				const parent = document.activeElement.closest(`.${t.options.classPrefix}qualityselection-selector`);
				if (!parent) {
					// focus is outside the control; close menu
					t.hideQualitySelector();
				}
			}, 0);
		}, 100));
	},

	addClick (media, classPrefix) {
		const t = this;
		const radios = t.qualityselectionButton.querySelectorAll('input[type=radio]');
		var dash = false;

		if(media.dashPlayer) dash = true;

		for (let i = 0, total = radios.length; i < total; i++) {
			// handle clicks to the source radio buttons
			radios[i].addEventListener('click', function() {
				// set aria states
				this.setAttribute('aria-selected', true);
				this.checked = true;

				const otherRadios = this.closest(`.${classPrefix}qualityselection-selector`).querySelectorAll('input[type=radio]');

				for (let j = 0, radioTotal = otherRadios.length; j < radioTotal; j++) {
					if (otherRadios[j] !== this) {
						otherRadios[j].setAttribute('aria-selected', 'false');
						otherRadios[j].removeAttribute('checked');
					}
				}

				if(dash) {
					t.switchSourceDash(this.value, media.dashPlayer, classPrefix);
				} else {
					t.switchSourceHls(this.value, media.hlsPlayer, classPrefix);
				}				
			});
		}
	},

	switchSourceDash (selectedSrc, dashPlayer, classPrefix) {
		const t = this;

		if (selectedSrc === "auto") {
			dashPlayer.setAutoSwitchQualityFor('video', true);
		} else if (dashPlayer.getQualityFor('video') !== selectedSrc) {
			dashPlayer.setAutoSwitchQualityFor('video', false);
			dashPlayer.setQualityFor('video', selectedSrc);
		} else {
			dashPlayer.setAutoSwitchQualityFor('video', false);
		}
		t.updateQualityButton(dashPlayer.getQualityFor('video'), classPrefix);
	},

	switchSourceHls (selectedSrc, hlsPlayer, classPrefix) {
		const t = this;
		
		if (selectedSrc === "auto") {
			hlsPlayer.nextLevel = -1;
		} else {
			hlsPlayer.nextLevel = selectedSrc;
		}		
		t.updateQualityButton(hlsPlayer.nextLevel, classPrefix);
	},
	
	/**
	 *
	 * @param {String} height
	 * @param {String} qualityIndex
	 * @param {Boolean} isCurrent
	 */
	addQualityButton (height, qualityIndex, isCurrent)  {
		const t = this;		
		
		t.qualityselectionButton.querySelector('ul').innerHTML += `<li>` +
			`<input type="radio" name="${t.id}_qualityselectionchooser" id="${t.id}_qualityselectionchooser_${height}" ` +
				`role="menuitemradio" value="${qualityIndex}" ${(isCurrent ? 'checked="checked"' : '')} aria-selected="${isCurrent}"/>` +
			`<label for="${t.id}_qualityselectionchooser_${height}" aria-hidden="true">${height}</label>` +
		`</li>`;

		t.adjustQualityBox();
	},
	
	/**
	 * @param {String} qualityIndex
	 * @param {String} classPrefix
	 */
	updateQualityButton (qualityIndex, classPrefix)  {
		const t = this;
		
		var radio = t.qualityselectionButton.querySelector('input[value="' + qualityIndex + '"] + label');
		radio.style.color = "#21f8f8";
		const otherRadios = radio.closest(`.${classPrefix}qualityselection-selector`).querySelectorAll('input[type=radio] + label');
		for (let j = 0, radioTotal = otherRadios.length; j < radioTotal; j++) {
			if (otherRadios[j] !== radio) {
				otherRadios[j].style.color = "#fff";
			}
		}
	},

	/**
	 *
	 */
	adjustQualityBox ()  {
		const t = this;
		// adjust the size of the outer box
		t.qualityselectionButton.querySelector(`.${t.options.classPrefix}qualityselection-selector`).style.height =
			`${parseFloat(t.qualityselectionButton.querySelector(`.${t.options.classPrefix}qualityselection-selector ul`).offsetHeight)}px`;
	},

	/**
	 *
	 */
	hideQualitySelector ()  {

		const t = this;

		if (t.qualityselectionButton === undefined || !t.qualityselectionButton.querySelector('input[type=radio]')) {
			return;
		}

		const
			selector = t.qualityselectionButton.querySelector(`.${t.options.classPrefix}qualityselection-selector`),
			radios = selector.querySelectorAll('input[type=radio]')
		;
		selector.setAttribute('aria-expanded', 'false');
		selector.setAttribute('aria-hidden', 'true');
		mejs.Utils.addClass(selector, `${t.options.classPrefix}offscreen`);

		// make radios not focusable
		for (let i = 0, total = radios.length; i < total; i++) {
			radios[i].setAttribute('tabindex', '-1');
		}
	},

	/**
	 *
	 */
	showQualitySelector ()  {

		const t = this;

		if (t.qualityselectionButton === undefined || !t.qualityselectionButton.querySelector('input[type=radio]')) {
			return;
		}

		const
			selector = t.qualityselectionButton.querySelector(`.${t.options.classPrefix}qualityselection-selector`),
			radios = selector.querySelectorAll('input[type=radio]')
		;
		selector.setAttribute('aria-expanded', 'true');
		selector.setAttribute('aria-hidden', 'false');
		mejs.Utils.removeClass(selector, `${t.options.classPrefix}offscreen`);

		// make radios not focusable
		for (let i = 0, total = radios.length; i < total; i++) {
			radios[i].setAttribute('tabindex', '0');
		}
}
    

   
});