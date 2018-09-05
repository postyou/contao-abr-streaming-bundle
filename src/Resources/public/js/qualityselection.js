'use strict';

/**
 * [Name of feature]
 *
 * [Description]
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
		// add to list
		let hoverTimeout;

		player.qualityselectionButton = document.createElement('div');
        player.qualityselectionButton.className = `${t.options.classPrefix}button ${t.options.classPrefix}qualityselection-button`;
        player.qualityselectionButton.innerHTML =
            `<button type="button" role="button" aria-haspopup="true" aria-owns="${t.id}" tabindex="0"></button>` +
            `<div class="${t.options.classPrefix}qualityselection-selector ${t.options.classPrefix}offscreen" role="menu" aria-expanded="false" aria-hidden="true"><ul></ul></div>`;

		t.addControlElement(player.qualityselectionButton, 'qualityselection');
		if (media.dashPlayer) {
        media.dashPlayer.on(dashjs.MediaPlayer.events.STREAM_INITIALIZED, function() {
		
        for (let i = 0, total = media.dashPlayer.getBitrateInfoListFor('video').length; i < total; i++) {
            const s = media.dashPlayer.getBitrateInfoListFor('video')[i];
		    sources.unshift(s);
		}

		if (sources.length <= 1) {
			return;
		}		
                
        for (let i = 0, total = sources.length; i < total; i++) {
			const src = sources[i];
			if (src.mediaType === "video") {
				if (src.qualityselectionIndex === media.dashPlayer.getQualityFor("video") && !media.dashPlayer.getAutoSwitchQualityFor("video")) var isCurrent = true;
				player.addQualityButton(src.height + "p", src.qualityselectionIndex, isCurrent);				
			}
		}
		player.addQualityButton("Automatisch", "auto", media.dashPlayer.getAutoSwitchQualityFor("video"));				

		// hover
		player.qualityselectionButton.addEventListener('mouseover', () => {
			clearTimeout(hoverTimeout);
			player.showQualitySelector();
		});
		player.qualityselectionButton.addEventListener('mouseout', () => {
			hoverTimeout = setTimeout(() => {
				player.hideQualitySelector();
			}, 0);
		});

		// close menu when tabbing away
		player.qualityselectionButton.addEventListener('focusout', mejs.Utils.debounce(() => {
			// Safari triggers focusout multiple times
			// Firefox does NOT support e.relatedTarget to see which element
			// just lost focus, so wait to find the next focused element
			setTimeout(() => {
				const parent = document.activeElement.closest(`.${t.options.classPrefix}qualityselection-selector`);
				if (!parent) {
					// focus is outside the control; close menu
					player.hideQualitySelector();
				}
			}, 0);
		}, 100));

		const radios = player.qualityselectionButton.querySelectorAll('input[type=radio]');

		for (let i = 0, total = radios.length; i < total; i++) {
			// handle clicks to the source radio buttons
			radios[i].addEventListener('click', function() {
				// set aria states
				this.setAttribute('aria-selected', true);
				this.checked = true;

				const otherRadios = this.closest(`.${t.options.classPrefix}qualityselection-selector`).querySelectorAll('input[type=radio]');

				for (let j = 0, radioTotal = otherRadios.length; j < radioTotal; j++) {
					if (otherRadios[j] !== this) {
						otherRadios[j].setAttribute('aria-selected', 'false');
						otherRadios[j].removeAttribute('checked');
					}
				}

				const selectedSrc = this.value;

				if (selectedSrc === "auto") {
					media.dashPlayer.setAutoSwitchQualityFor('video', true);
				} else if (media.dashPlayer.getQualityFor('video') !== selectedSrc) {
					media.dashPlayer.setAutoSwitchQualityFor('video', false);
					media.dashPlayer.setQualityFor('video', selectedSrc);
				} else {
					media.dashPlayer.setAutoSwitchQualityFor('video', false);
				}
			});
		}
		player.updateQualityButton(media.dashPlayer.getQualityFor('video'), t.options.classPrefix);
		media.dashPlayer.on(dashjs.MediaPlayer.events.QUALITY_CHANGE_REQUESTED, function() {
			player.updateQualityButton(media.dashPlayer.getQualityFor('video'), t.options.classPrefix);
		}, this);

		}, this);
	}
	},
	
	/**
	 *
	 * @param {String} height
	 * @param {Boolean} isCurrent
	 */
	addQualityButton (height, qualityselectionIndex, isCurrent)  {
		const t = this;
		
		
		t.qualityselectionButton.querySelector('ul').innerHTML += `<li>` +
			`<input type="radio" name="${t.id}_qualityselectionchooser" id="${t.id}_qualityselectionchooser_${height}" ` +
				`role="menuitemradio" value="${qualityselectionIndex}" ${(isCurrent ? 'checked="checked"' : '')} aria-selected="${isCurrent}"/>` +
			`<label for="${t.id}_qualityselectionchooser_${height}" aria-hidden="true">${height}</label>` +
		`</li>`;

		t.adjustQualityBox();
	},
	
	/**
	 *
	 */
	updateQualityButton (qualityselectionIndex, classPrefix)  {
		var radio = this.qualityselectionButton.querySelector('input[value="' + qualityselectionIndex + '"] + label');
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