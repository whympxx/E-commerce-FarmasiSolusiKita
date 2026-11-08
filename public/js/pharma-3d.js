/**
 * Pharma3D — animasi 3D bertema farmasi (kapsul, tablet, salib medis, vitamin).
 * Butuh Three.js r128 (window.THREE) sudah dimuat lebih dulu.
 *
 * Pemakaian:
 *   Pharma3D.mount(document.getElementById('scene'), { objects: 14, hero: true });
 *
 * Opsi:
 *   objects      jumlah benda kecil yang melayang (default 12)
 *   hero         tampilkan kapsul besar di tengah (default true)
 *   heroOffsetX  geser posisi kapsul besar ke kanan/kiri (default 0)
 *   particles    jumlah partikel latar (default 220)
 *   area         batas sebaran benda kecil {xMin, xMax, yMin, yMax} (default seluruh layar)
 *   palette      daftar warna kapsul, pasangan [warnaA, warnaB]
 */
(function () {
    'use strict';

    const DEFAULT_PALETTE = [
        [0x2563eb, 0xffffff],
        [0x1e40af, 0x60a5fa],
        [0x059669, 0xffffff],
        [0xdc2626, 0xfde68a],
        [0x7c3aed, 0xe9d5ff],
        [0xf59e0b, 0xffffff],
    ];

    function mount(container, options) {
        const THREE = window.THREE;
        if (!container || !THREE || !webglAvailable()) {
            if (container) container.classList.add('pharma3d-fallback');
            return null;
        }

        const opts = Object.assign({
            objects: 12,
            hero: true,
            heroOffsetX: 0,
            particles: 220,
            palette: DEFAULT_PALETTE,
        }, options || {});
        const area = Object.assign({ xMin: -9, xMax: 9, yMin: -5, yMax: 5 }, opts.area || {});

        const reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // ---- Scene dasar ----
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(45, 1, 0.1, 100);
        camera.position.set(0, 0, 12);

        const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
        renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
        renderer.outputEncoding = THREE.sRGBEncoding;
        renderer.domElement.className = 'pharma3d-canvas';
        container.appendChild(renderer.domElement);

        scene.add(new THREE.AmbientLight(0xffffff, 0.65));
        const keyLight = new THREE.DirectionalLight(0xffffff, 0.9);
        keyLight.position.set(5, 6, 8);
        scene.add(keyLight);
        const blueLight = new THREE.PointLight(0x60a5fa, 1.2, 40);
        blueLight.position.set(-6, -3, 6);
        scene.add(blueLight);
        const warmLight = new THREE.PointLight(0xfbbf24, 0.5, 40);
        warmLight.position.set(6, -4, 4);
        scene.add(warmLight);

        const world = new THREE.Group();
        scene.add(world);

        // ---- Pembuat benda ----
        function glossy(color) {
            return new THREE.MeshPhysicalMaterial({
                color: color, roughness: 0.25, metalness: 0.05, clearcoat: 0.8, clearcoatRoughness: 0.2,
            });
        }

        function makeCapsule(colorA, colorB, radius, length) {
            const g = new THREE.Group();
            const half = length / 2;
            const cylGeo = new THREE.CylinderGeometry(radius, radius, half, 40, 1, true);
            const capGeo = new THREE.SphereGeometry(radius, 40, 20, 0, Math.PI * 2, 0, Math.PI / 2);

            const matA = glossy(colorA);
            const matB = glossy(colorB);

            const cylA = new THREE.Mesh(cylGeo, matA);
            cylA.position.y = half / 2;
            const capA = new THREE.Mesh(capGeo, matA);
            capA.position.y = half;

            const cylB = new THREE.Mesh(cylGeo, matB);
            cylB.position.y = -half / 2;
            const capB = new THREE.Mesh(capGeo, matB);
            capB.position.y = -half;
            capB.rotation.x = Math.PI;

            // Cincin tipis di sambungan kapsul
            const ring = new THREE.Mesh(
                new THREE.TorusGeometry(radius * 1.005, radius * 0.06, 12, 48),
                glossy(0xe2e8f0)
            );
            ring.rotation.x = Math.PI / 2;

            g.add(cylA, capA, cylB, capB, ring);
            return g;
        }

        function makeTablet(color, radius) {
            const g = new THREE.Group();
            const body = new THREE.Mesh(new THREE.CylinderGeometry(radius, radius, radius * 0.38, 48), glossy(color));
            const line = new THREE.Mesh(
                new THREE.BoxGeometry(radius * 1.7, radius * 0.05, radius * 0.08),
                new THREE.MeshStandardMaterial({ color: 0xcbd5e1, roughness: 0.6 })
            );
            line.position.y = radius * 0.19;
            g.add(body, line);
            return g;
        }

        function makeCross(color, size) {
            const g = new THREE.Group();
            const mat = glossy(color);
            const depth = size * 0.3;
            g.add(new THREE.Mesh(new THREE.BoxGeometry(size, size * 0.34, depth), mat));
            g.add(new THREE.Mesh(new THREE.BoxGeometry(size * 0.34, size, depth), mat));
            return g;
        }

        function makeVitamin(color, radius) {
            return new THREE.Mesh(
                new THREE.SphereGeometry(radius, 32, 16),
                new THREE.MeshPhysicalMaterial({
                    color: color, roughness: 0.1, transmission: 0, clearcoat: 1, emissive: color, emissiveIntensity: 0.15,
                })
            );
        }

        // ---- Susun benda ----
        const floaters = [];
        const rand = (a, b) => a + Math.random() * (b - a);

        function addFloater(mesh, x, y, z, scale) {
            mesh.position.set(x, y, z);
            mesh.scale.setScalar(scale);
            mesh.rotation.set(rand(0, Math.PI), rand(0, Math.PI), rand(0, Math.PI));
            mesh.userData = {
                baseY: y,
                baseScale: scale,
                hover: 0,
                speed: rand(0.4, 1.0),
                phase: rand(0, Math.PI * 2),
                spin: new THREE.Vector3(rand(-0.6, 0.6), rand(-0.8, 0.8), rand(-0.4, 0.4)),
            };
            world.add(mesh);
            floaters.push(mesh);
        }

        let hero = null;
        if (opts.hero) {
            const [a, b] = opts.palette[0];
            hero = makeCapsule(a, b, 0.9, 2.6);
            hero.position.set(opts.heroOffsetX, 0, 1.5);
            hero.rotation.z = Math.PI / 4;
            world.add(hero);

            // Orbit tablet kecil di sekitar kapsul besar
            hero.userData.orbit = new THREE.Group();
            hero.userData.orbit.position.copy(hero.position);
            for (let i = 0; i < 3; i++) {
                const t = makeTablet(0xffffff, 0.32);
                const angle = (i / 3) * Math.PI * 2;
                t.position.set(Math.cos(angle) * 2.4, Math.sin(angle) * 0.6, Math.sin(angle) * 2.4);
                hero.userData.orbit.add(t);
            }
            world.add(hero.userData.orbit);
        }

        for (let i = 0; i < opts.objects; i++) {
            const kind = i % 4;
            const [a, b] = opts.palette[i % opts.palette.length];
            let mesh;
            if (kind === 0 || kind === 1) mesh = makeCapsule(a, b, 0.32, 0.95);
            else if (kind === 2) mesh = makeTablet(i % 3 === 0 ? 0xfef3c7 : 0xffffff, 0.42);
            else mesh = i % 2 ? makeCross(0x10b981, 0.8) : makeVitamin(0xf59e0b, 0.28);

            // Sebar di sekeliling, hindari tengah agar tidak menutupi kapsul utama
            let x, y;
            do {
                x = rand(area.xMin, area.xMax);
                y = rand(area.yMin, area.yMax);
            } while (opts.hero && Math.abs(x - opts.heroOffsetX) < 2.6 && Math.abs(y) < 2.4);
            addFloater(mesh, x, y, rand(-5, 1.5), rand(0.75, 1.25));
        }

        // Partikel latar
        if (opts.particles > 0) {
            const positions = new Float32Array(opts.particles * 3);
            for (let i = 0; i < opts.particles; i++) {
                positions[i * 3] = rand(-16, 16);
                positions[i * 3 + 1] = rand(-10, 10);
                positions[i * 3 + 2] = rand(-12, -2);
            }
            const geo = new THREE.BufferGeometry();
            geo.setAttribute('position', new THREE.BufferAttribute(positions, 3));
            const points = new THREE.Points(geo, new THREE.PointsMaterial({
                color: 0x93c5fd, size: 0.06, transparent: true, opacity: 0.7,
            }));
            points.userData.isParticles = true;
            world.add(points);
            world.userData.particles = points;
        }

        // ---- Interaksi mouse ----
        const pointer = new THREE.Vector2(0, 0);
        const target = new THREE.Vector2(0, 0);
        const raycaster = new THREE.Raycaster();
        let pointerInside = false;

        function onMove(e) {
            const rect = container.getBoundingClientRect();
            pointer.x = ((e.clientX - rect.left) / rect.width) * 2 - 1;
            pointer.y = -((e.clientY - rect.top) / rect.height) * 2 + 1;
            pointerInside = pointer.x >= -1 && pointer.x <= 1 && pointer.y >= -1 && pointer.y <= 1;
        }
        window.addEventListener('pointermove', onMove, { passive: true });

        // Klik: benda di dekat kursor "meloncat" berputar
        container.addEventListener('click', function () {
            raycaster.setFromCamera(pointer, camera);
            const hits = raycaster.intersectObjects(floaters.concat(hero ? [hero] : []), true);
            if (hits.length) {
                let obj = hits[0].object;
                while (obj.parent && obj.parent !== world) obj = obj.parent;
                obj.userData.burst = 1;
            }
        });

        // ---- Ukuran ----
        function resize() {
            const w = container.clientWidth || 1;
            const h = container.clientHeight || 1;
            renderer.setSize(w, h, false);
            camera.aspect = w / h;
            // Layar sempit: mundurkan kamera agar semua benda tetap terlihat
            camera.position.z = w / h < 1 ? 16 : 12;
            camera.updateProjectionMatrix();
        }
        resize();
        if (window.ResizeObserver) new ResizeObserver(resize).observe(container);
        else window.addEventListener('resize', resize);

        // ---- Animasi ----
        const clock = new THREE.Clock();
        let running = true;
        let visible = true;

        if ('IntersectionObserver' in window) {
            new IntersectionObserver(function (entries) {
                visible = entries[0].isIntersecting;
            }).observe(container);
        }

        function frame() {
            if (!running) return;
            requestAnimationFrame(frame);
            if (!visible || document.hidden) return;

            const dt = Math.min(clock.getDelta(), 0.05);
            const t = clock.elapsedTime;

            // Kamera/dunia mengikuti mouse dengan halus (efek parallax)
            target.x += ((pointerInside ? pointer.x : 0) - target.x) * 0.05;
            target.y += ((pointerInside ? pointer.y : 0) - target.y) * 0.05;
            world.rotation.y = target.x * 0.35;
            world.rotation.x = -target.y * 0.2;

            // Sorotan benda di bawah kursor
            let hovered = null;
            if (pointerInside) {
                raycaster.setFromCamera(pointer, camera);
                const hits = raycaster.intersectObjects(floaters, true);
                if (hits.length) {
                    hovered = hits[0].object;
                    while (hovered.parent && hovered.parent !== world) hovered = hovered.parent;
                }
            }
            container.style.cursor = hovered ? 'pointer' : '';

            floaters.forEach(function (m) {
                const d = m.userData;
                d.hover += ((m === hovered ? 1 : 0) - d.hover) * 0.12;
                const burst = d.burst || 0;
                m.position.y = d.baseY + Math.sin(t * d.speed + d.phase) * 0.35;
                m.rotation.x += d.spin.x * dt * (1 + d.hover * 3 + burst * 12);
                m.rotation.y += d.spin.y * dt * (1 + d.hover * 3 + burst * 12);
                m.rotation.z += d.spin.z * dt;
                m.scale.setScalar(d.baseScale * (1 + d.hover * 0.35 + burst * 0.3));
                if (burst) d.burst = Math.max(0, burst - dt * 1.2);
            });

            if (hero) {
                const burst = hero.userData.burst || 0;
                hero.rotation.y += dt * (0.6 + burst * 8);
                hero.rotation.z = Math.PI / 4 + Math.sin(t * 0.6) * 0.15;
                hero.position.y = Math.sin(t * 0.8) * 0.25;
                if (burst) hero.userData.burst = Math.max(0, burst - dt * 1.2);
                const orbit = hero.userData.orbit;
                orbit.rotation.y += dt * 0.5;
                orbit.position.y = hero.position.y;
                orbit.children.forEach(function (c) { c.rotation.x += dt; c.rotation.z += dt * 0.7; });
            }

            if (world.userData.particles) {
                world.userData.particles.rotation.y = t * 0.02;
            }

            renderer.render(scene, camera);
        }

        if (reduceMotion) {
            renderer.render(scene, camera);
        } else {
            frame();
        }

        return {
            destroy: function () {
                running = false;
                window.removeEventListener('pointermove', onMove);
                renderer.dispose();
                container.removeChild(renderer.domElement);
            },
        };
    }

    function webglAvailable() {
        try {
            const c = document.createElement('canvas');
            return !!(window.WebGLRenderingContext && (c.getContext('webgl') || c.getContext('experimental-webgl')));
        } catch (e) {
            return false;
        }
    }

    window.Pharma3D = { mount: mount };
})();
