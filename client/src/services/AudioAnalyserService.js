export default class AudioAnalyserService {
  constructor() {
    const AudioContextElement = window.AudioContext || window.webkitAudioContext;
    this.audioContext = new AudioContextElement();
  }

  initializeAnalyser() {
    const audio = document.querySelector('.c-player audio');
    const source = this.audioContext.createMediaElementSource(audio);
    const analyser = this.audioContext.createAnalyser();

    source.connect(analyser);
    analyser.connect(this.audioContext.destination);
    analyser.fftSize = 2048;
    analyser.minDecibels = -90;
    analyser.maxDecibels = 0;

    return analyser;
  }


}
