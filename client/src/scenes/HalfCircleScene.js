import * as THREE from 'three';

export default class HalfCircleScene {
  constructor(canvas, audioAnalyser, radius, numberOfCircles, numberOfParticles) {
    this.canvas = canvas;
    this.audioAnalyser = audioAnalyser;
    this.width = window.innerWidth;
    this.height = window.innerHeight;

    this.radius = radius;
    this.numberOfCircles = numberOfCircles;
    this.numberOfParticles = numberOfParticles;
    this.linewidth = 0.01;

    this.allVertices = [];
    this.objects = [];

    this.group = new THREE.Object3D();

    this.initializeAnalyser();
    this.createScene();
    this.createParticles();
  }

  initializeAnalyser() {
    this.audioAnalyser.smoothingTimeConstant = 0.75;
    const bufferLength = this.audioAnalyser.frequencyBinCount;
    this.frequencyData = new Uint8Array(bufferLength);
    this.localFrequencyData = new Uint8Array(bufferLength);
  }

  createScene() {
    this.camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 1, 10000);
    this.camera.position.y = 8;
    this.camera.position.z = 10;

    this.scene = new THREE.Scene();

    this.material = new THREE.MeshBasicMaterial({ color: 0xffffff, wireframe: false });

    this.renderer = new THREE.WebGLRenderer({ antialias: true, canvas: this.canvas, alpha: true });
    this.renderer.setClearColor(new THREE.Color(0xffffff));
    this.renderer.setSize(window.innerWidth, window.innerHeight);

    window.addEventListener('resize', this.onWindowResize.bind(this), false);
  }

  createParticles() {
    for (let i = 0; i < this.numberOfCircles; i += 1) {
      const points = this.generatePoints(this.radius, i);
      const points2 = this.generatePoints(this.radius * (1 - this.linewidth), i);

      const vertices = HalfCircleScene.generateVertices(points, points2);
      this.allVertices.push(vertices);

      const geometry = new THREE.BufferGeometry();
      geometry.addAttribute('position', new THREE.BufferAttribute(vertices, 3));

      const object = new THREE.Mesh(geometry, this.material);
      object.r_coof = 1 + (this.linewidth * 0.8 * i);
      object.wave_type = i;

      this.objects.push(object);
      this.group.add(object);
      object.rotation.y = (Math.PI / 180) * 180;
    }

    this.scene.add(this.group);
  }

  generatePoints(radius, waveType) {
    const newPositions = [];

    for (let i = 0; i <= this.numberOfParticles; i += 1) {
      this.audioAnalyser.getByteTimeDomainData(this.localFrequencyData);
      const angle = (Math.PI / this.numberOfParticles) * i;
      const waveHeight = this.localFrequencyData[i];
      let radiusAddon = 0;

      switch (waveType) {
        case 0:
          radiusAddon = Math.cos(waveHeight / 75);
          break;
        case 1:
          radiusAddon = Math.sin(waveHeight / 75);
          break;
        case 2:
          radiusAddon = Math.cos((angle + ((Math.PI / 180) * 120)) * (waveHeight / 50));
          break;
        case 3:
          radiusAddon = Math.cos((angle + ((Math.PI / 90) * 240)) * (waveHeight / 50));
          break;
        case 4:
          radiusAddon = Math.sin((angle + ((Math.PI / 90) * 120)) * (waveHeight / 75));
          break;
        default:
          radiusAddon = Math.cos(waveHeight / 75);
      }

      // if (wave_type === 4) radius_addon = Math.tan(wave_height / 150);

      const x = (radius + radiusAddon) * Math.cos(angle);
      const y = (radius + radiusAddon) * Math.sin(angle);

      newPositions.push([x, y, 0]);
    }

    return newPositions;
  }

  static generateVertices(points, points2) {
    const vertexPositions = [];

    for (let i = 0; i < points.length - 1; i += 1) {
      vertexPositions.push(points[i], points2[i], points[i + 1]);
      vertexPositions.push(points2[i], points2[i + 1], points[i + 1]);
    }

    vertexPositions.push(points[points.length - 1], points2[points.length - 1], points[0]);

    const vertices = new Float32Array(vertexPositions.length * 3);

    for (let i = 0; i < vertexPositions.length; i += 1) {
      vertices[i * 3] = vertexPositions[i][0];
      vertices[(i * 3) + 1] = vertexPositions[i][1];
      vertices[(i * 3) + 2] = vertexPositions[i][2];
    }

    return vertices;
  }

  static updateVertices(points, points2, vertices) {
    const mutatableVertices = vertices;
    const vertexPositions = [];

    for (let i = 0; i < points.length - 1; i += 1) {
      vertexPositions.push(points[i], points2[i], points[i + 1]);
      vertexPositions.push(points2[i], points2[i + 1], points[i + 1]);
    }

    vertexPositions.push(points[points.length - 1], points2[points.length - 1], points[0]);

    for (let i = 0; i < vertexPositions.length; i += 1) {
      mutatableVertices[i * 3] = vertexPositions[i][0];
      mutatableVertices[(i * 3) + 1] = vertexPositions[i][1];
      mutatableVertices[(i * 3) + 2] = vertexPositions[i][2];
    }
  }

  animateParticles() {
    this.animationFrame = requestAnimationFrame(this.animateParticles.bind(this));
    this.renderParticles();
  }

  renderParticles() {
    this.audioAnalyser.getByteFrequencyData(this.frequencyData);

    this.objects.forEach((object, index) => {
      const mutatableObject = object;

      const red = (`0 ${parseInt(20, 10).toString(16)}`).slice(-2);
      const green = (`0 ${parseInt(72, 10).toString(16)}`).slice(-2);
      const formattedBlue = this.frequencyData[index] * 10 > 10 ? this.frequencyData[index] * 10 : '240';
      const blue = (`0 ${parseInt(formattedBlue, 10).toString(16)}`).slice(-2);
      mutatableObject.material.color.setHex(`0x${red}${green}${blue}`);

      const radius = this.radius + (this.frequencyData[2 * index] / 50);
      const points = this.generatePoints(radius, object.wave_type);
      const points2 = this.generatePoints(radius * (1 - this.linewidth), object.wave_type);
      HalfCircleScene.updateVertices(points, points2, this.allVertices[index]);
      mutatableObject.geometry.attributes.position.needsUpdate = true;
    });

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
