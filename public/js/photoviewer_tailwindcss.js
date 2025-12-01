/**
 * Photo Viewer - Plain JS with Tailwind CSS
 * 
 * Reads markdown content directly to extract images and provide gallery viewer
 * Supports both markdown format: ![alt](url) and HTML format: <img src="">
 */

class PhotoViewer {
    constructor(markdownPath) {
        this.markdownPath = markdownPath;
        this.images = [];
        this.currentIndex = 0;
        this.modal = null;
        this.isOpen = false;

        this.init();
    }

    /**
     * Initialize the photo viewer
     */
    async init() {
        // Fetch markdown content directly
        try {
            const response = await fetch(this.markdownPath);
            const markdownContent = await response.text();
            
            // Extract images from markdown
            this.extractImagesFromMarkdown(markdownContent);
            
            // If images found, setup event listeners
            if (this.images.length > 0) {
                this.setupEventListeners();
                this.createModal();
            }
        } catch (error) {
            console.error('Error loading markdown:', error);
        }
    }

    /**
     * Extract images from markdown content
     * Supports: ![alt](url) and <img src="">
     */
    extractImagesFromMarkdown(content) {
        const images = [];

        // Extract markdown images: ![alt](url)
        const markdownPattern = /!\[([^\]]*)\]\(([^)]+)\)/g;
        let match;
        while ((match = markdownPattern.exec(content)) !== null) {
            const alt = match[1] || 'Image';
            const src = match[2];
            if (src && src.trim()) {
                images.push({ src: src.trim(), alt });
            }
        }

        // Extract HTML images: <img src="...">
        const htmlPattern = /<img[^>]+src=["\']([^"\']+)["\'][^>]*(?:alt=["\']([^"\']*)["\'])?/gi;
        while ((match = htmlPattern.exec(content)) !== null) {
            const src = match[1];
            const alt = match[2] || 'Image';
            if (src && src.trim()) {
                images.push({ src: src.trim(), alt });
            }
        }

        // Remove duplicates based on src
        this.images = Array.from(new Map(
            images.map(img => [img.src, img])
        ).values());
    }

    /**
     * Setup event listeners for image clicks
     */
    setupEventListeners() {
        // Wait for zero-md to render, then attach listeners
        const checkImages = () => {
            // Find all images in the rendered content
            const zeroMdElements = document.querySelectorAll('zero-md');
            
            zeroMdElements.forEach(zeroMd => {
                // Get the shadow root or rendered content
                const root = zeroMd.shadowRoot || zeroMd;
                const images = root.querySelectorAll('img');
                
                images.forEach((img, index) => {
                    img.style.cursor = 'pointer';
                    img.addEventListener('click', () => this.open(index));
                });
            });

            // Also handle cover images if any
            const coverImages = document.querySelectorAll('figure img, [data-gallery] img');
            coverImages.forEach(img => {
                img.style.cursor = 'pointer';
                img.addEventListener('click', (e) => {
                    // Find matching image by src
                    const matchIndex = this.images.findIndex(
                        image => image.src === img.src
                    );
                    if (matchIndex !== -1) {
                        this.open(matchIndex);
                    }
                });
            });
        };

        // Check if images are already loaded
        checkImages();

        // Also check after a delay in case zero-md is still rendering
        setTimeout(checkImages, 500);
        setTimeout(checkImages, 1000);
    }

    /**
     * Create modal HTML
     */
    createModal() {
        const modal = document.createElement('div');
        modal.id = 'photo-viewer-modal';
        modal.className = 'hidden fixed inset-0 z-50 bg-black bg-opacity-90';
        modal.innerHTML = `
            <!-- Close Button - floating di atas gambar -->
            <button id="photo-viewer-close" 
                    class="absolute top-4 right-4 md:top-8 md:right-8 bg-white text-black rounded-lg p-3 md:p-4 transition-all duration-200 z-50 shadow-2xl hover:bg-gray-100">
                <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            
            <!-- Content Wrapper (untuk layout center) -->
            <div class="flex flex-col items-center justify-center w-full h-full">
                
                <!-- Image Container with padding -->
                <div class="relative w-full h-full flex items-center justify-center p-4 md:p-8">
                    
                    <!-- Image Wrapper (limit max size) -->
                    <div class="w-full h-full max-w-full md:max-w-4xl lg:max-w-5xl max-h-[calc(100vh-8rem)] flex items-center justify-center">
                        <!-- Actual Image -->
                        <img id="photo-viewer-image" 
                             src="" 
                             alt="" 
                             class="max-w-full max-h-full object-contain rounded-lg shadow-2xl transition-opacity duration-150">
                    </div>
                </div>
            </div>

            <!-- Navigation Controls -->
            <div class="absolute bottom-0 left-0 right-0 pb-4 md:pb-6 flex justify-center z-50">
                <div class="flex items-center gap-3 md:gap-4 bg-white px-6 py-3 md:px-8 md:py-4 rounded-lg shadow-2xl">
                    
                    <!-- Left Arrow Button -->
                    <button id="photo-viewer-prev" 
                            class="text-black p-2 md:p-3 transition-all duration-200 rounded-md hover:bg-gray-200">
                        <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>

                    <!-- Photo Counter -->
                    <div class="text-black text-sm md:text-base font-semibold px-3 md:px-4 min-w-[80px] md:min-w-[100px] text-center">
                        <span id="photo-viewer-counter">1</span> / <span id="photo-viewer-total">1</span>
                    </div>

                    <!-- Right Arrow Button -->
                    <button id="photo-viewer-next" 
                            class="text-black p-2 md:p-3 transition-all duration-200 rounded-md hover:bg-gray-200">
                        <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        this.modal = modal;

        // Setup button listeners
        document.getElementById('photo-viewer-close').addEventListener('click', (e) => {
            e.stopPropagation();
            this.close();
        });
        
        // Modal click to close (click anywhere on dark background)
        modal.addEventListener('click', (e) => {
            // Only close if clicking directly on modal (not on image or controls)
            if (e.target === modal) {
                this.close();
            }
        });
        
        document.getElementById('photo-viewer-prev').addEventListener('click', (e) => {
            e.stopPropagation();
            this.prev();
        });
        document.getElementById('photo-viewer-next').addEventListener('click', (e) => {
            e.stopPropagation();
            this.next();
        });

        // Prevent image click from closing
        document.getElementById('photo-viewer-image').addEventListener('click', (e) => {
            e.stopPropagation();
        });

        // Setup keyboard listeners
        document.addEventListener('keydown', (e) => {
            if (!this.isOpen) return;

            switch (e.key) {
                case 'ArrowLeft':
                case 'a':
                case 'A':
                    e.preventDefault();
                    this.prev();
                    break;
                case 'ArrowRight':
                case 'd':
                case 'D':
                    e.preventDefault();
                    this.next();
                    break;
                case 'Escape':
                    this.close();
                    break;
            }
        });

        // Update counter
        document.getElementById('photo-viewer-total').textContent = this.images.length;
    }

    /**
     * Open gallery at specific index
     */
    open(index) {
        if (this.images.length === 0) return;

        this.currentIndex = Math.max(0, Math.min(index, this.images.length - 1));
        this.isOpen = true;
        this.modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent background scroll
        this.updateImage();
    }

    /**
     * Close gallery
     */
    close() {
        this.isOpen = false;
        this.modal.classList.add('hidden');
        document.body.style.overflow = ''; // Restore scroll
    }

    /**
     * Navigate to next image
     */
    next() {
        this.currentIndex = (this.currentIndex + 1) % this.images.length;
        this.updateImage();
    }

    /**
     * Navigate to previous image
     */
    prev() {
        this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
        this.updateImage();
    }

    /**
     * Update displayed image
     */
    updateImage() {
        const image = this.images[this.currentIndex];
        if (!image) return;

        const imgElement = document.getElementById('photo-viewer-image');
        const counter = document.getElementById('photo-viewer-counter');

        // Fade transition
        imgElement.style.opacity = '0';
        
        setTimeout(() => {
            imgElement.src = image.src;
            imgElement.alt = image.alt;
            counter.textContent = this.currentIndex + 1;
            imgElement.style.opacity = '1';
        }, 150);
    }
}

/**
 * Initialize photo viewer when DOM is ready
 */
document.addEventListener('DOMContentLoaded', () => {
    // Get markdown path from the zero-md element
    const zeroMdElement = document.querySelector('zero-md');
    
    if (zeroMdElement && zeroMdElement.hasAttribute('src')) {
        const markdownPath = zeroMdElement.getAttribute('src');
        new PhotoViewer(markdownPath);
    }
});