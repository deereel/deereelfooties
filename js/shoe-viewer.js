/**
 * ShoeViewer - A simple 3D shoe model viewer using Three.js
 */
class ShoeViewer {
  constructor(canvas) {
    this.canvas = canvas;
    this.scene = null;
    this.camera = null;
    this.renderer = null;
    this.model = null;
    this.controls = null;
    this.currentStyle = 'oxford';
    this.currentColor = 'black';
    this.currentMaterial = 'calf';
    
    this.init();
  }
  
  init() {
    // Create scene
    this.scene = new THREE.Scene();
    this.scene.background = new THREE.Color(0xf5f5f5);
    
    // Create camera
    this.camera = new THREE.PerspectiveCamera(
      45, 
      this.canvas.clientWidth / this.canvas.clientHeight, 
      0.1, 
      1000
    );
    this.camera.position.set(0, 0, 5);
    
    // Create renderer
    this.renderer = new THREE.WebGLRenderer({
      canvas: this.canvas,
      antialias: true
    });
    this.renderer.setSize(this.canvas.clientWidth, this.canvas.clientHeight);
    this.renderer.setPixelRatio(window.devicePixelRatio);
    this.renderer.shadowMap.enabled = true;
    
    // Add lights
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
    this.scene.add(ambientLight);
    
    const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
    directionalLight.position.set(1, 1, 1);
    directionalLight.castShadow = true;
    this.scene.add(directionalLight);
    
    // Add controls
    this.controls = new THREE.OrbitControls(this.camera, this.renderer.domElement);
    this.controls.enableDamping = true;
    this.controls.dampingFactor = 0.05;
    
    // Load model
    this.loadModel();
    
    // Handle window resize
    window.addEventListener('resize', () => this.onWindowResize());
    
    // Start animation loop
    this.animate();
  }
  
  loadModel() {
    // Remove existing model if any
    if (this.model) {
      this.scene.remove(this.model);
    }
    
    // Create a placeholder shoe model (in a real app, you'd load actual 3D models)
    const modelPath = `/models/${this.currentStyle}.glb`;
    
    const loader = new THREE.GLTFLoader();
    loader.load(
      modelPath,
      (gltf) => {
        this.model = gltf.scene;
        this.model.scale.set(1, 1, 1);
        this.model.position.set(0, -1, 0);
        this.model.traverse((child) => {
          if (child.isMesh) {
            child.castShadow = true;
            child.receiveShadow = true;
            this.applyMaterialAndColor(child);
          }
        });
        this.scene.add(this.model);
      },
      (xhr) => {
        console.log((xhr.loaded / xhr.total * 100) + '% loaded');
      },
      (error) => {
        console.error('Error loading model:', error);
        // Create a placeholder if model loading fails
        this.createPlaceholderModel();
      }
    );
  }
  
  createPlaceholderModel() {
    // Create a simple shoe-like shape as placeholder
    const geometry = new THREE.BoxGeometry(1.5, 0.5, 3);
    const material = new THREE.MeshPhongMaterial({ color: this.getColorValue() });
    
    this.model = new THREE.Mesh(geometry, material);
    this.model.position.set(0, -1, 0);
    this.model.castShadow = true;
    this.model.receiveShadow = true;
    
    // Add some details to make it look more like a shoe
    const toeCap = new THREE.Mesh(
      new THREE.SphereGeometry(0.5, 16, 16, 0, Math.PI * 2, 0, Math.PI / 2),
      material
    );
    toeCap.position.set(0, -0.25, 1.5);
    toeCap.rotation.x = Math.PI / 2;
    toeCap.castShadow = true;
    
    const heel = new THREE.Mesh(
      new THREE.BoxGeometry(1.5, 0.8, 0.8),
      material
    );
    heel.position.set(0, -0.15, -1.2);
    heel.castShadow = true;
    
    this.model.add(toeCap);
    this.model.add(heel);
    
    this.scene.add(this.model);
  }
  
  applyMaterialAndColor(mesh) {
    let material;
    
    switch (this.currentMaterial) {
      case 'suede':
        material = new THREE.MeshStandardMaterial({
          color: this.getColorValue(),
          roughness: 0.9,
          metalness: 0.1
        });
        break;
      case 'patent':
        material = new THREE.MeshStandardMaterial({
          color: this.getColorValue(),
          roughness: 0.1,
          metalness: 0.2,
          clearcoat: 1.0,
          clearcoatRoughness: 0.1
        });
        break;
      case 'calf':
      default:
        material = new THREE.MeshStandardMaterial({
          color: this.getColorValue(),
          roughness: 0.5,
          metalness: 0.1
        });
        break;
    }
    
    mesh.material = material;
  }
  
  getColorValue() {
    switch (this.currentColor) {
      case 'black': return 0x000000;
      case 'brown': return 0x8B4513;
      case 'tan': return 0xD2B48C;
      case 'burgundy': return 0x722F37;
      case 'navy': return 0x1e3a8a;
      default: return 0x000000;
    }
  }
  
  setStyle(style) {
    this.currentStyle = style;
    this.loadModel();
  }
  
  setColor(color) {
    this.currentColor = color;
    if (this.model) {
      this.model.traverse((child) => {
        if (child.isMesh) {
          this.applyMaterialAndColor(child);
        }
      });
    }
  }
  
  setMaterial(material) {
    this.currentMaterial = material;
    if (this.model) {
      this.model.traverse((child) => {
        if (child.isMesh) {
          this.applyMaterialAndColor(child);
        }
      });
    }
  }
  
  onWindowResize() {
    this.camera.aspect = this.canvas.clientWidth / this.canvas.clientHeight;
    this.camera.updateProjectionMatrix();
    this.renderer.setSize(this.canvas.clientWidth, this.canvas.clientHeight);
  }
  
  animate() {
    requestAnimationFrame(() => this.animate());
    
    if (this.controls) {
      this.controls.update();
    }
    
    if (this.model) {
      this.model.rotation.y += 0.005;
    }
    
    this.renderer.render(this.scene, this.camera);
  }
  
  render() {
    this.renderer.render(this.scene, this.camera);
  }
}