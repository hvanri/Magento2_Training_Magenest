
define([
    'ko',
    'uiComponent',
    'mage/storage',
    'mage/translate',
    'jquery'
], function (ko, Component, storage, $t, $) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Magenest_CustomUi/color-switcher',
            colorOptionsUrl: '/customui/color/options', // Ajax endpoint
            tracks: {
                selectedColor: true,
                panelVisible: true
            }
        },

        /**
         * Initialize component
         */
        initialize: function () {
            this._super();

            // Observable properties
            this.colorOptions = ko.observableArray([]);
            this.selectedColor = ko.observable(null);
            this.panelVisible = ko.observable(false);
            this.isLoading = ko.observable(true);
            this.errorMessage = ko.observable('');
            this.showNotification = ko.observable(false);
            this.notificationMessage = ko.observable('');

            // Initialize CSS variables
            this.initializeCSSVariables();

            // Load colors
            this.loadColors();

            // Load saved color preference
            this.loadSavedColor();

            console.log('Color Switcher KnockoutJS: Initialized');

            return this;
        },

        /**
         * Initialize CSS variables to prevent errors
         */
        initializeCSSVariables: function () {
            const defaultPrimary = '#0077be';
            const defaultSecondary = '#4da6d9';

            document.documentElement.style.setProperty('--primary-color', defaultPrimary);
            document.documentElement.style.setProperty('--secondary-color', defaultSecondary);
        },

        /**
         * Load color options
         */
        loadColors: function () {
            const self = this;

            // Demo colors fallback
            const demoColors = [
                {
                    title: $t('Ocean Blue'),
                    colorCode: '#0077be',
                    secondaryColor: this.lightenColor('#0077be', 25),
                    index: 0
                },
                {
                    title: $t('Sunset Orange'),
                    colorCode: '#ff6b35',
                    secondaryColor: this.lightenColor('#ff6b35', 25),
                    index: 1
                },
                {
                    title: $t('Forest Green'),
                    colorCode: '#2d5a3d',
                    secondaryColor: this.lightenColor('#2d5a3d', 25),
                    index: 2
                },
                {
                    title: $t('Royal Purple'),
                    colorCode: '#6a4c93',
                    secondaryColor: this.lightenColor('#6a4c93', 25),
                    index: 3
                },
                {
                    title: $t('Cherry Red'),
                    colorCode: '#d63031',
                    secondaryColor: this.lightenColor('#d63031', 25),
                    index: 4
                },
                {
                    title: $t('Golden Yellow'),
                    colorCode: '#fdcb6e',
                    secondaryColor: this.lightenColor('#fdcb6e', 25),
                    index: 5
                }
            ];

            try {
                // Try to load from configuration (Ajax call)
                if (this.colorOptionsUrl) {
                    console.log('Attempting to load colors from:', this.colorOptionsUrl);

                    $.ajax({
                        url: this.colorOptionsUrl,
                        type: 'GET',
                        dataType: 'json',
                        timeout: 5000,
                        success: function (response) {
                            console.log('AJAX response received:', response);

                            if (response && response.colors) {
                                // Nếu response.colors là object, log kiểu dữ liệu
                                console.log('Type of response.colors:', typeof response.colors);

                                // Kiểm tra xem có phải array hay object
                                if (Array.isArray(response.colors)) {
                                    console.log('Colors is an array with length:', response.colors.length);
                                    const processedColors = response.colors.map(function (color, index) {
                                        console.log('Processing color:', color, 'at index', index);
                                        return {
                                            title: color.color_title || 'Color ' + (index + 1),
                                            colorCode: color.color_code,
                                            secondaryColor: self.lightenColor(color.color_code, 25),
                                            index: index
                                        };
                                    });
                                    self.colorOptions(processedColors);
                                } else {
                                    // Nếu object, log keys và chuyển thành array
                                    const keys = Object.keys(response.colors);
                                    console.log('Colors is an object with keys:', keys);

                                    const processedColors = keys.map(function(key, index) {
                                        const color = response.colors[key];
                                        console.log('Processing color key:', key, color);
                                        return {
                                            title: color.color_title || 'Color ' + (index + 1),
                                            colorCode: color.color_code,
                                            secondaryColor: self.lightenColor(color.color_code, 25),
                                            index: index
                                        };
                                    });
                                    self.colorOptions(processedColors);
                                }
                            } else {
                                console.log('No colors found in response, using demo colors');
                                self.colorOptions(demoColors);
                            }

                            self.isLoading(false);
                            console.log('Colors loaded, total:', self.colorOptions().length);
                        },
                        error: function (xhr, status, error) {
                            console.warn('Failed to load colors from server, using demo colors:', error);
                            console.log('XHR object:', xhr);
                            self.colorOptions(demoColors);
                            self.isLoading(false);
                        }
                    });
                } else {
                    console.log('No colorOptionsUrl defined, using demo colors');
                    this.colorOptions(demoColors);
                    this.isLoading(false);
                }
            } catch (e) {
                console.warn('Error loading colors:', e);
                this.colorOptions(demoColors);
                this.isLoading(false);
            }
        },

        /**
         * Toggle panel visibility
         */
        togglePanel: function () {
            this.panelVisible(!this.panelVisible());
        },

        /**
         * Select a color
         */
        selectColor: function (colorData) {
            console.log('Color selected:', colorData.title);

            // Update selected color
            this.selectedColor(colorData);

            // Apply background gradient
            this.applyBackgroundGradient(colorData);

            // Save to localStorage
            this.saveColorPreference(colorData);

            // Show notification
            //this.showColorChangeNotification(colorData.title);

            // Hide panel after selection (mobile UX)
            if (window.innerWidth <= 768) {
                setTimeout(() => {
                    this.panelVisible(false);
                }, 1000);
            }
        },

        /**
         * Handle keyboard navigation
         */
        handleKeyDown: function (data, event) {
            if (event.keyCode === 13 || event.keyCode === 32) { // Enter or Space
                event.preventDefault();
                this.selectColor(data);
                return false;
            }
            return true;
        },

        /**
         * Reset to default color
         */
        resetColor: function () {
            console.log('Reset color clicked');

            // Clear localStorage
            try {
                localStorage.removeItem('selected_color');
            } catch (e) {
                console.warn('Could not clear localStorage:', e);
            }

            // Reset to first color or default
            const firstColor = this.colorOptions()[0];
            if (firstColor) {
                this.selectColor(firstColor);
            }

            // Reset CSS and classes
            this.resetToDefaultTheme();
        },

        /**
         * Select random color
         */
        randomColor: function () {
            const colors = this.colorOptions();
            if (colors.length > 0) {
                const randomIndex = Math.floor(Math.random() * colors.length);
                this.selectColor(colors[randomIndex]);
            }
        },

        /**
         * Apply background gradient
         */
        applyBackgroundGradient: function (colorData) {
            if (!colorData || !colorData.colorCode) {
                console.warn('Invalid color data provided');
                return;
            }

            const primaryColor = colorData.colorCode;
            const secondaryColor = colorData.secondaryColor;

            // Check CSS variables support
            const supportsVariables = window.CSS && CSS.supports && CSS.supports('background', 'var(--primary-color)');

            if (supportsVariables) {
                // Set CSS variables
                document.documentElement.style.setProperty('--primary-color', primaryColor);
                document.documentElement.style.setProperty('--secondary-color', secondaryColor);

                // Remove existing gradient classes
                $('body').removeClass(function (index, className) {
                    return (className.match(/(^|\s)gradient-bg-\S+/g) || []).join(' ');
                });

                // Add new gradient class
                const gradientClass = 'gradient-bg-' + ((colorData.index % 4) + 1);
                $('body').addClass(gradientClass);

                console.log('Applied CSS variables and gradient class:', gradientClass);
            } else {
                // Fallback for older browsers
                const gradientPatterns = [
                    `linear-gradient(-45deg, ${primaryColor}, ${secondaryColor})`,
                    `linear-gradient(45deg, ${primaryColor}, ${secondaryColor})`,
                    `linear-gradient(90deg, ${primaryColor}, ${secondaryColor})`,
                    `linear-gradient(135deg, ${primaryColor}, ${secondaryColor})`
                ];

                const selectedPattern = gradientPatterns[colorData.index % 4];
                $('body').css({
                    'background': selectedPattern,
                    'background-size': '400% 400%',
                    'animation': 'gradientShift 8s ease infinite'
                });

                console.log('Applied fallback gradient:', selectedPattern);
            }
        },

        /**
         * Reset to default Magento theme
         */
        resetToDefaultTheme: function () {
            const defaultColor = getComputedStyle(document.body).backgroundColor || '#ffffff';

            // Reset CSS variables
            document.documentElement.style.setProperty('--primary-color', defaultColor);
            document.documentElement.style.setProperty('--secondary-color', defaultColor);

            // Remove gradient classes
            $('body').removeClass(function (index, className) {
                return (className.match(/(^|\s)gradient-bg-\S+/g) || []).join(' ');
            });

            // Clear selected color
            this.selectedColor(null);
        },

        /**
         * Save color preference to localStorage
         */
        saveColorPreference: function (colorData) {
            try {
                const saveData = {
                    title: colorData.title,
                    colorCode: colorData.colorCode,
                    secondaryColor: colorData.secondaryColor,
                    index: colorData.index,
                    timestamp: new Date().getTime()
                };

                localStorage.setItem('selected_color', JSON.stringify(saveData));
                console.log('Color preference saved');
            } catch (e) {
                console.warn('Could not save color preference:', e);
            }
        },

        /**
         * Load saved color preference
         */
        loadSavedColor: function () {
            const self = this;

            // Wait for colors to load first
            const checkAndLoad = function () {
                if (self.colorOptions().length > 0) {
                    // Sau khi mảng màu load xong, tìm saved color trong localStorage
                    const savedColor = localStorage.getItem('selected_color');
                    if (savedColor) {
                        const colorData = JSON.parse(savedColor);
                        const savedColorOption = self.colorOptions().find(c => c.index === colorData.index);
                        if (savedColorOption) {
                            self.selectColor(savedColorOption); // apply màu saved
                            return;
                        }
                    }
                    // fallback: chọn màu đầu tiên
                    if (self.colorOptions().length > 0) {
                        self.selectColor(self.colorOptions()[0]);
                    }
                } else {
                    // nếu chưa load xong -> retry sau 100ms
                    setTimeout(checkAndLoad, 100);
                }
            };
            checkAndLoad();
        },

        /**
         * Show color change notification
         */
        showColorChangeNotification: function (colorTitle) {
            this.notificationMessage($t('Color changed to: %1').replace('%1', colorTitle));
            this.showNotification(true);

            // Auto-hide after 3 seconds
            setTimeout(() => {
                this.showNotification(false);
            }, 3000);
        },

        /**
         * Lighten a hex color by percentage
         */
        lightenColor: function (color, percent) {
            const hex = color.replace('#', '');
            if (hex.length !== 6 || !/^[0-9A-Fa-f]+$/.test(hex)) {
                return '#ffffff'; // fallback
            }

            const num = parseInt(hex, 16);
            const amt = Math.round(2.55 * percent);

            const R = Math.min(255, Math.max(0, (num >> 16) + amt));
            const B = Math.min(255, Math.max(0, (num >> 8 & 0x00FF) + amt));
            const G = Math.min(255, Math.max(0, (num & 0x0000FF) + amt));

            return '#' + ((R << 16) + (B << 8) + G).toString(16).padStart(6, '0');
        },

        /**
         * Debug function
         */
        debug: function () {
            return {
                colorOptions: this.colorOptions(),
                selectedColor: this.selectedColor(),
                isLoading: this.isLoading(),
                panelVisible: this.panelVisible()
            };
        }
    });
});
