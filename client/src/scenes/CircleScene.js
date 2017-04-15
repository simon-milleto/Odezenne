import * as THREE from 'three';

export default class CircleScene {
  constructor(canvas, audioAnalyser) {
    this.canvas = canvas;
    this.audioAnalyser = audioAnalyser;
    this.width = window.innerWidth;
    this.height = window.innerHeight;
    this.particles = [];

    this.initializeAnalyser();
    this.createScene();
    this.createParticles();
    this.animateParticles();
  }

  initializeAnalyser() {
    this.audioAnalyser.smoothingTimeConstant = 0.75;
    const bufferLength = this.audioAnalyser.frequencyBinCount;
    this.frequencyData = new Uint8Array(bufferLength);
  }

  createScene() {
    this.camera =
      new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 1, 10000);
    this.camera.position.z = 150;

    this.scene = new THREE.Scene();

    this.geometry = new THREE.SphereGeometry(1, 32, 32);
    this.material = new THREE.MeshBasicMaterial({ color: 0xffffff });

    this.renderer = new THREE.WebGLRenderer({ canvas: this.canvas });
    this.renderer.setClearColor(new THREE.Color(0xffffff));
    this.renderer.setSize(window.innerWidth, window.innerHeight);

    window.addEventListener('resize', this.onWindowResize.bind(this), false);
  }

  createParticles() {
    for (let i = 0; i < this.frequencyData.length; i += 1) {
      const particle = new THREE.Mesh(this.geometry, this.material);
      particle.position.x = (Math.cos((i * Math.PI) / 24) * 100);
      particle.position.y = (Math.sin((i * Math.PI) / 24) * 100);
      this.scene.add(particle);
      this.particles[i] = particle;
    }
  }

  animateParticles() {
    this.animationFrame = requestAnimationFrame(this.animateParticles.bind(this));
    this.renderParticles();
  }

  renderParticles() {
    this.audioAnalyser.getByteTimeDomainData(this.frequencyData);
    this.camera.position.set(
      this.frequencyData[0] / 10,
      this.frequencyData[0] / 10,
      100 + (this.frequencyData[0]),
    );

    for (let i = 0; i < this.frequencyData.length; i += 1) {
      const particle = this.particles[i];
      particle.position.x = (Math.cos((i * Math.PI) / 24) * ((this.frequencyData[i] / 0.8) - 50));
      particle.position.y = (Math.sin((i * Math.PI) / 24) * ((this.frequencyData[i] / 0.8) - 50));
      const red = (`0 ${parseInt(20, 10).toString(16)}`).slice(-2);
      const green = (`0 ${parseInt(72, 10).toString(16)}`).slice(-2);
      const blue = (`0 ${parseInt(this.frequencyData[i] * i * 0.7, 10).toString(16)}`).slice(-2);
      particle.material.color.setHex(`0x${red}${green}${blue}`);
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
