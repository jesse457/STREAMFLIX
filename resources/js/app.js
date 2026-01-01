
import videojs from 'video.js';
import 'videojs-contrib-quality-levels';
import 'videojs-http-source-selector';

// Import Video.js CSS
import 'video.js/dist/video-js.css';

// Make videojs available globally (needed for the blade view)
window.videojs = videojs;
