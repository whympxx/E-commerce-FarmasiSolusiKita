// Admin Reports JavaScript
// Prevent duplicate initialization
if (window.reportsInitialized) {
    console.log('Reports already initialized, skipping...');
    return;
}

window.reportsInitialized = true;
console.log('Initializing reports...');

document.addEventListener('DOMContentLoaded', function() {
    // Check if already initialized
    if (window.reportsDOMInitialized) {
        console.log('Reports DOM already initialized, skipping...');
        return;
    }
    
    window.reportsDOMInitialized = true;
    console.log('Initializing reports DOM...');
    
    // Initialize charts
    initializeCharts();
    
    // Initialize filter form
    initializeFilterForm();
    
    // Initialize period buttons
    initializePeriodButtons();
    
    // Add loading states
    addLoadingStates();
    
    console.log('Reports initialization complete');
});

// Initialize Charts
function initializeCharts() {
    // Prevent duplicate chart initialization
    if (window.salesChart || window.categoryChart) {
        console.log('Charts already initialized, skipping...');
        return;
    }
    
    console.log('Initializing charts...');
    
    // Sales Chart
    const salesCtx = document.getElementById('salesChart');
    if (salesCtx) {
        console.log('Sales chart canvas found, initializing...');
        try {
            const salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: getLast7Days(),
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: generateSampleData(7),
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#667eea',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Pendapatan: Rp ' + numberFormat(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6c757d',
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            color: '#6c757d',
                            font: {
                                size: 12
                            },
                            callback: function(value) {
                                return 'Rp ' + numberFormat(value);
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
        
        // Store chart instance for later use
        window.salesChart = salesChart;
        console.log('Sales chart initialized');
        } catch (error) {
            console.error('Error initializing sales chart:', error);
        }
    } else {
        console.warn('Sales chart canvas not found');
    }
    
    // Category Chart
    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx) {
        console.log('Category chart canvas found, initializing...');
        try {
            const categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Obat Bebas', 'Obat Keras', 'Vitamin', 'Alat Kesehatan', 'Lainnya'],
                datasets: [{
                    data: [30, 25, 20, 15, 10],
                    backgroundColor: [
                        '#667eea',
                        '#764ba2',
                        '#f093fb',
                        '#f5576c',
                        '#4facfe'
                    ],
                    borderWidth: 0,
                    hoverBorderWidth: 3,
                    hoverBorderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#667eea',
                        borderWidth: 1,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + percentage + '%';
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });
        
        // Store chart instance for later use
        window.categoryChart = categoryChart;
        console.log('Category chart initialized');
        } catch (error) {
            console.error('Error initializing category chart:', error);
        }
    } else {
        console.warn('Category chart canvas not found');
    }
}

// Initialize Filter Form (prevent duplicate)
function initializeFilterForm() {
    console.log('Initializing filter form...');
    
    const filterForm = document.getElementById('reportFilterForm');
    if (filterForm) {
        console.log('Filter form found, setting up listeners...');
    } else {
        console.warn('Filter form not found');
        return;
    }
        // Auto-submit on report type change
        const reportTypeSelect = document.getElementById('report_type');
        if (reportTypeSelect) {
            console.log('Setting up report type change listener...');
        } else {
            console.warn('Report type select not found');
        }
            
            // Remove existing listeners to prevent duplicates
            if (reportTypeSelect.changeHandler) {
                reportTypeSelect.removeEventListener('change', reportTypeSelect.changeHandler);
                console.log('Removed existing report type change handler');
            }
            
            // Create new handler
            reportTypeSelect.changeHandler = function() {
                console.log('Report type changed to:', this.value);
                filterForm.submit();
            };
            
            // Add new listener
            reportTypeSelect.addEventListener('change', reportTypeSelect.changeHandler);
            console.log('Report type change listener added');
        }
        
        // Date validation
        const dateFrom = document.getElementById('date_from');
        const dateTo = document.getElementById('date_to');
        
        if (dateFrom && dateTo) {
            console.log('Setting up date validation listeners...');
        } else {
            console.warn('Date inputs not found');
        }
            
            // Remove existing listeners to prevent duplicates
            if (dateFrom.changeHandler) {
                dateFrom.removeEventListener('change', dateFrom.changeHandler);
                console.log('Removed existing date from change handler');
            }
            if (dateTo.changeHandler) {
                dateTo.removeEventListener('change', dateTo.changeHandler);
                console.log('Removed existing date to change handler');
            }
            
            // Create new handlers
            dateFrom.changeHandler = function() {
                console.log('Date from changed to:', this.value);
                dateTo.min = this.value;
            };
            
            dateTo.changeHandler = function() {
                console.log('Date to changed to:', this.value);
                dateFrom.max = this.value;
            };
            
            // Add new listeners
            dateFrom.addEventListener('change', dateFrom.changeHandler);
            dateTo.addEventListener('change', dateTo.changeHandler);
            console.log('Date validation listeners added');
        }
    }
}

// Initialize Period Buttons (prevent duplicate)
function initializePeriodButtons() {
    console.log('Initializing period buttons...');
    
    const periodButtons = document.querySelectorAll('[data-period]');
    console.log('Found', periodButtons.length, 'period buttons');
    if (periodButtons.length === 0) {
        console.warn('No period buttons found');
        return;
    }
    periodButtons.forEach(button => {
        console.log('Setting up period button listener for:', button.dataset.period);
        
        // Remove existing listeners to prevent duplicates
        if (button.clickHandler) {
            button.removeEventListener('click', button.clickHandler);
            console.log('Removed existing period button handler for:', button.dataset.period);
        }
        
        // Create new handler
        button.clickHandler = function() {
            console.log('Period button clicked:', this.dataset.period);
            
            // Remove active class from all buttons
            periodButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Update chart data based on period
            const period = this.dataset.period;
            updateChartData(period);
        };
        
        // Add new listener
        button.addEventListener('click', button.clickHandler);
        console.log('Period button listener added for:', button.dataset.period);
    });
    
    // Set default active button (7 days)
    const defaultButton = document.querySelector('[data-period="7"]');
    if (defaultButton) {
        console.log('Setting default active button to 7 days');
        defaultButton.classList.add('active');
        console.log('Default button activated');
    } else {
        console.warn('Default period button (7 days) not found');
    }
}

// Update Chart Data
function updateChartData(period) {
    console.log('Updating chart data for period:', period);
    
    if (window.salesChart) {
        const days = parseInt(period);
        const labels = getLastNDays(days);
        const data = generateSampleData(days);
        
        window.salesChart.data.labels = labels;
        window.salesChart.data.datasets[0].data = data;
        window.salesChart.update('active');
        console.log('Chart data updated for period:', period);
        
        // Show loading animation
        showLoadingAnimation();
        
        // Simulate API call delay
        setTimeout(() => {
            hideLoadingAnimation();
        }, 1000);
    }
}

// Generate Sample Data
function generateSampleData(days) {
    console.log('Generating sample data for', days, 'days');
    
    const data = [];
    for (let i = 0; i < days; i++) {
        data.push(Math.floor(Math.random() * 5000000) + 1000000);
    }
    console.log('Generated', data.length, 'data points');
    return data;
}

// Get Last N Days
function getLastNDays(n) {
    if (typeof n !== 'number' || n <= 0) {
        console.warn('getLastNDays received invalid number:', n);
        return [];
    }
    
    console.log('Getting last', n, 'days');
    const dates = [];
    for (let i = n - 1; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        dates.push(formatDate(date));
    }
    console.log('Generated', dates.length, 'date labels');
    return dates;
}

// Get Last 7 Days
function getLast7Days() {
    console.log('Getting last 7 days');
    return getLastNDays(7);
}

// Format Date
function formatDate(date) {
    if (!(date instanceof Date)) {
        console.warn('formatDate received non-Date:', date);
        return 'Invalid Date';
    }
    const options = { month: 'short', day: 'numeric' };
    return date.toLocaleDateString('id-ID', options);
}

// Number Format
function numberFormat(number) {
    if (typeof number !== 'number') {
        console.warn('numberFormat received non-number:', number);
        return '0';
    }
    return new Intl.NumberFormat('id-ID').format(number);
}

// Add Loading States (prevent duplicate)
function addLoadingStates() {
    console.log('Adding loading states...');
    
    const cards = document.querySelectorAll('.stat-card');
    console.log('Found', cards.length, 'stat cards');
    if (cards.length === 0) {
        console.warn('No stat cards found');
        return;
    }
    cards.forEach(card => {
        console.log('Setting up loading state for card');
        
        // Remove existing listeners to prevent duplicates
        if (card.loadingHandler) {
            card.removeEventListener('click', card.loadingHandler);
            console.log('Removed existing loading state handler');
        }
        
        // Create new handler
        card.loadingHandler = function() {
            console.log('Card clicked, showing loading state');
            this.classList.add('loading');
            setTimeout(() => {
                this.classList.remove('loading');
            }, 2000);
        };
        
        // Add new listener
        card.addEventListener('click', card.loadingHandler);
        console.log('Loading state listener added');
    });
}

// Show Loading Animation
function showLoadingAnimation() {
    console.log('Showing loading animation...');
    
    const chartContainer = document.querySelector('#salesChart');
    if (chartContainer && chartContainer.parentElement) {
        chartContainer.parentElement.classList.add('loading');
    } else {
        console.warn('Sales chart container not found');
    }
}

// Hide Loading Animation
function hideLoadingAnimation() {
    console.log('Hiding loading animation...');
    
    const chartContainer = document.querySelector('#salesChart');
    if (chartContainer && chartContainer.parentElement) {
        chartContainer.parentElement.classList.remove('loading');
    } else {
        console.warn('Sales chart container not found');
    }
}

// Export Functions
function exportReport(type, format) {
    console.log('Exporting report:', type, format);
    
    // Show loading state
    const exportButtons = document.querySelectorAll('.btn[href*="export"]');
    console.log('Found', exportButtons.length, 'export buttons');
    if (exportButtons.length === 0) {
        console.warn('No export buttons found');
        return;
    }
    exportButtons.forEach(btn => {
        btn.classList.add('loading');
        btn.disabled = true;
    });
    
    // Simulate export process
    setTimeout(() => {
        console.log('Export simulation completed');
        exportButtons.forEach(btn => {
            btn.classList.remove('loading');
            btn.disabled = false;
        });
        console.log('Export buttons re-enabled');
        
        // Show success message
        showNotification('Laporan berhasil diexport!', 'success');
    }, 2000);
}

// Show Notification
function showNotification(message, type = 'info') {
    console.log('Showing notification:', message, type);
    
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    console.log('Notification added to DOM');
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        console.log('Auto-removing notification');
        if (notification.parentNode) {
            notification.remove();
            console.log('Notification removed from DOM');
        }
    }, 5000);
}

// Add event listeners for export buttons (prevent duplicate)
if (!window.exportListenersAdded) {
    window.exportListenersAdded = true;
    console.log('Adding export event listeners...');
    
    // Remove existing listeners to prevent duplicates
    if (document.exportHandler) {
        document.removeEventListener('click', document.exportHandler);
        console.log('Removed existing export handler');
    }
    
    // Create new handler
    document.exportHandler = function(e) {
        if (e.target.matches('.btn[href*="export"]')) {
            console.log('Export button clicked');
            e.preventDefault();
            const href = e.target.getAttribute('href');
            const url = new URL(href, window.location.origin);
            const type = url.searchParams.get('type');
            const format = url.searchParams.get('format');
            exportReport(type, format);
        }
    };
    
    // Add new listener
    document.addEventListener('click', document.exportHandler);
    console.log('Export event listener added');
}

// Responsive Chart Resize (prevent duplicate)
if (!window.resizeListenersAdded) {
    window.resizeListenersAdded = true;
    console.log('Adding resize event listeners...');
    
    // Remove existing listeners to prevent duplicates
    if (window.resizeHandler) {
        window.removeEventListener('resize', window.resizeHandler);
        console.log('Removed existing resize handler');
    }
    
    // Create new handler
    window.resizeHandler = function() {
        console.log('Window resized, updating charts...');
        if (window.salesChart) {
            window.salesChart.resize();
            console.log('Sales chart resized');
        }
        if (window.categoryChart) {
            window.categoryChart.resize();
            console.log('Category chart resized');
        }
    };
    
    // Add new listener
    window.addEventListener('resize', window.resizeHandler);
    console.log('Resize event listener added');
}

// Add smooth scrolling for anchor links (prevent duplicate)
if (!window.smoothScrollListenersAdded) {
    window.smoothScrollListenersAdded = true;
    console.log('Adding smooth scroll event listeners...');
    
    const anchors = document.querySelectorAll('a[href^="#"]');
    console.log('Found', anchors.length, 'anchor links');
    if (anchors.length === 0) {
        console.warn('No anchor links found');
        return;
    }
    anchors.forEach(anchor => {
        console.log('Setting up smooth scroll for anchor:', anchor.getAttribute('href'));
        
        // Remove existing listeners to prevent duplicates
        if (anchor.scrollHandler) {
            anchor.removeEventListener('click', anchor.scrollHandler);
            console.log('Removed existing smooth scroll handler for:', anchor.getAttribute('href'));
        }
        
        // Create new handler
        anchor.scrollHandler = function (e) {
            console.log('Smooth scrolling to:', this.getAttribute('href'));
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        };
        
        // Add new listener
        anchor.addEventListener('click', anchor.scrollHandler);
        console.log('Smooth scroll listener added for:', anchor.getAttribute('href'));
    });
}

// Add keyboard shortcuts (prevent duplicate)
if (!window.keyboardListenersAdded) {
    window.keyboardListenersAdded = true;
    console.log('Adding keyboard event listeners...');
    
    // Remove existing listeners to prevent duplicates
    if (document.keyboardHandler) {
        document.removeEventListener('keydown', document.keyboardHandler);
        console.log('Removed existing keyboard handler');
    }
    
    // Create new handler
    document.keyboardHandler = function(e) {
        // Ctrl/Cmd + R to refresh reports
        if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
            console.log('Keyboard shortcut: Refresh reports');
            e.preventDefault();
            document.getElementById('reportFilterForm').submit();
        }
        
        // Ctrl/Cmd + E to export
        if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
            console.log('Keyboard shortcut: Export');
            e.preventDefault();
            const exportBtn = document.querySelector('.btn[href*="export"]');
            if (exportBtn) {
                exportBtn.click();
            }
        }
    };
    
    // Add new listener
    document.addEventListener('keydown', document.keyboardHandler);
    console.log('Keyboard event listener added');
}

// Add print functionality
function printReport() {
    console.log('Printing report...');
    window.print();
}

// Add print button event listener (prevent duplicate)
if (!window.printListenersAdded) {
    window.printListenersAdded = true;
    console.log('Adding print event listeners...');
    
    // Remove existing listeners to prevent duplicates
    if (document.printHandler) {
        document.removeEventListener('click', document.printHandler);
        console.log('Removed existing print handler');
    }
    
    // Create new handler
    document.printHandler = function(e) {
        if (e.target.matches('.btn-print')) {
            console.log('Print button clicked');
            e.preventDefault();
            printReport();
        }
    };
    
    // Add new listener
    document.addEventListener('click', document.printHandler);
    console.log('Print event listener added');
} 