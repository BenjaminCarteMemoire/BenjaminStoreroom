import './main.css'
import { generateTOC } from './TOC';
import Swup from 'swup';
import SwupBodyClassPlugin from '@swup/body-class-plugin';

/*
 * ===
 *  INIT SWUP
 * ===
 */

const swup = new Swup({
    plugins: [new SwupBodyClassPlugin()]
});

swup.hooks.on('content:replace', () => {
    generateTOC();
});
document.addEventListener('DOMContentLoaded', () => {
    generateTOC();
})

/*
 * ===
 * MOBILE COMPATIBILITY
 * ===
 */
document.addEventListener('DOMContentLoaded', () => {
    let mobileAsideButton = document.getElementById('aside-button');
    let mobileAsideContainer = document.getElementById('aside-container');
    if (mobileAsideButton && mobileAsideContainer) {
        mobileAsideButton.addEventListener('click', () => {
            mobileAsideContainer.style.display = mobileAsideContainer.style.display === 'block' ? 'none' : 'block';
        })
    }
})