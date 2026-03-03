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
    mobileCompatibility();

});
document.addEventListener('DOMContentLoaded', () => {
    generateTOC();
    mobileCompatibility();
})

/*
 * ===
 * MOBILE COMPATIBILITY
 * ===
 */
function mobileCompatibility(){
    let mobileAsideButton = document.getElementById('aside-button');
    let mobileAsideContainer = document.getElementById('aside-container');
    if (mobileAsideButton && mobileAsideContainer) {
        mobileAsideButton.addEventListener('click', () => {
            mobileAsideContainer.style.display = mobileAsideContainer.style.display === 'block' ? 'none' : 'block';
        })
    }
}