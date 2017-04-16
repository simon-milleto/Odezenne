import * as THREE from 'three';

export default class GridScene {
  constructor(particlesPerXLine, particlesPerYLine, lineSpacement, canvas, audioAnalyser) {
    this.particlesPerXLine = particlesPerXLine;
    this.particlesPerYLine = particlesPerYLine;
    this.lineSpacement = lineSpacement;
    this.canvas = canvas;
    this.audioAnalyser = audioAnalyser;
    this.particles = [];

    this.initializeAnalyser();
    this.createScene();
    this.createParticles();
  }

  initializeAnalyser() {
    this.audioAnalyser.smoothingTimeConstant = 0.75;
    const bufferLength = this.audioAnalyser.frequencyBinCount;
    this.frequencyData = new Uint8Array(bufferLength);
  }

  createScene() {
    this.camera =
      new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 1, 10000);
    this.camera.position.z = 1000000;

    this.scene = new THREE.Scene();

    this.geometry = new THREE.SphereGeometry(1, 32, 32);
    this.material = new THREE.MeshBasicMaterial({ color: 0xffffff });

    this.renderer = new THREE.WebGLRenderer({ canvas: this.canvas });
    this.renderer.setClearColor(new THREE.Color(0xffffff));
    this.renderer.setSize(window.innerWidth, window.innerHeight);

    window.addEventListener('resize', this.onWindowResize.bind(this), false);
  }

  createParticles() {
    let i = 0;
    for (let ix = 0; ix < this.particlesPerXLine; ix += 1) {
      for (let iy = 0; iy < this.particlesPerYLine; iy += 1) {
        const particle = new THREE.Mesh(this.geometry, this.material);
        particle.position.x =
          (ix * this.lineSpacement) - ((this.particlesPerXLine * this.lineSpacement) / 2);
        particle.position.z =
          (iy * this.lineSpacement) - ((this.particlesPerYLine * this.lineSpacement) / 2);
        this.scene.add(particle);
        this.particles[i += 1] = particle;
      }
    }
  }

  animateParticles() {
    this.animationFrame = requestAnimationFrame(this.animateParticles.bind(this));
    this.renderParticles();
  }

  renderParticles() {
    this.audioAnalyser.getByteFrequencyData(this.frequencyData);
    this.camera.position.set(0, 300, 400);

    let i = 0;
    for (let ix = 0; ix < this.particlesPerXLine; ix += 1) {
      for (let iy = 0; iy < this.particlesPerYLine; iy += 1) {
        const particle = this.particles[i += 1];
        particle.position.y = (this.frequencyData[ix] * this.frequencyData[iy]) / 75;
        const red = (`0 ${parseInt(20, 10).toString(16)}`).slice(-2);
        const green = (`0 ${parseInt(72, 10).toString(16)}`).slice(-2);
        const formattedBlue = this.frequencyData[ix] * 10 > 10 ? this.frequencyData[ix] * 10 : '240';
        const blue = (`0 ${parseInt(formattedBlue, 10).toString(16)}`).slice(-2);
        particle.material.color.setHex(`0x${red}${green}${blue}`);
      }
    }
    this.renderer.render(this.scene, this.camera);
  }

  onWindowResize() {
    this.camera.aspect = window.innerWidth / window.innerHeight;
    this.camera.updateProjectionMatrix();

    this.renderer.setSize(window.innerWidth, window.innerHeight);
  }

  stopAnimation() {
    cancelAnimationFrame(this.animationFrame);
  }
}
