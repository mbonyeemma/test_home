@php
    // Include the form icons functions
    require_once app_path('Includes/formIcons.php');
    
    $allIcons = getFormIcons();
    $iconCategories = [
        'medical' => getIconsByCategory('medical'),
        'data' => getIconsByCategory('data'),
        'document' => getIconsByCategory('document'),
        'communication' => getIconsByCategory('communication'),
        'security' => getIconsByCategory('security'),
        'technology' => getIconsByCategory('technology'),
        'business' => getIconsByCategory('business'),
        'education' => getIconsByCategory('education'),
        'time' => getIconsByCategory('time'),
        'location' => getIconsByCategory('location'),
        'general' => getIconsByCategory('general')
    ];
@endphp

<div class="form-group">
    <label for="icon">Form Icon <span class="text-danger">*</span></label>
    <div class="icon-picker-container">
        <!-- Selected Icon Display -->
        <div class="selected-icon-display mb-3">
            <div class="selected-icon-preview">
                <i id="selected-icon-preview" class="fa fa-{{ $selectedIcon ?? 'file' }} fa-2x"></i>
                <span id="selected-icon-name" class="ml-2">{{ $selectedIcon ?? 'file' }}</span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#iconPickerModal">
                <i class="fa fa-search"></i> Choose Icon
            </button>
        </div>
        
        <!-- Hidden input to store selected icon -->
        <input type="hidden" name="icon" id="selected-icon-input" value="{{ $selectedIcon ?? 'file' }}">
        
        <!-- Icon Picker Modal -->
        <div class="modal fade" id="iconPickerModal" tabindex="-1" role="dialog" aria-labelledby="iconPickerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" style="max-width: 900px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="iconPickerModalLabel">Choose Form Icon</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Search Box -->
                        <div class="form-group">
                            <input type="text" id="icon-search" class="form-control" placeholder="Search icons...">
                        </div>
                        
                        <!-- Category Tabs -->
                        <ul class="nav nav-tabs" id="iconTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true">All Icons</a>
                            </li>
                            @foreach($iconCategories as $category => $icons)
                            <li class="nav-item">
                                <a class="nav-link" id="{{ $category }}-tab" data-toggle="tab" href="#{{ $category }}" role="tab" aria-controls="{{ $category }}" aria-selected="false">
                                    {{ ucfirst($category) }} ({{ count($icons) }})
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        
                        <!-- Tab Content -->
                        <div class="tab-content" id="iconTabContent">
                            <!-- All Icons Tab -->
                            <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                                <div class="icon-grid" id="all-icons-grid">
                                    @foreach($allIcons as $icon)
                                    <div class="icon-item" data-icon="{{ $icon }}" data-category="all">
                                        <i class="fa fa-{{ $icon }} fa-2x"></i>
                                        <span class="icon-name">{{ $icon }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Category Tabs -->
                            @foreach($iconCategories as $category => $icons)
                            <div class="tab-pane fade" id="{{ $category }}" role="tabpanel" aria-labelledby="{{ $category }}-tab">
                                <div class="icon-grid" id="{{ $category }}-icons-grid">
                                    @foreach($icons as $icon)
                                    <div class="icon-item" data-icon="{{ $icon }}" data-category="{{ $category }}">
                                        <i class="fa fa-{{ $icon }} fa-2x"></i>
                                        <span class="icon-name">{{ $icon }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="select-icon-btn" disabled>Select Icon</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.icon-picker-container {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 15px;
    background-color: #f9f9f9;
}

.selected-icon-display {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.selected-icon-preview {
    display: flex;
    align-items: center;
}

.icon-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    gap: 8px;
    max-height: 350px;
    overflow-y: auto;
    padding: 8px;
    border: 1px solid #eee;
    border-radius: 4px;
    margin-top: 10px;
}

.icon-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
    background-color: #fff;
}

.icon-item:hover {
    background-color: #f0f8ff;
    border-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.icon-item.selected {
    background-color: #007bff;
    color: white;
    border-color: #0056b3;
}

.icon-item i {
    margin-bottom: 5px;
    color: inherit;
}

.icon-name {
    font-size: 10px;
    text-align: center;
    word-break: break-all;
    line-height: 1.2;
}

.icon-item.selected .icon-name {
    color: white;
}

#icon-search {
    margin-bottom: 15px;
}

.nav-tabs .nav-link {
    font-size: 12px;
    padding: 8px 12px;
}

.nav-tabs .nav-link.active {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

.modal-body {
    padding: 15px;
}

.modal-header {
    padding: 15px 20px 10px 20px;
    border-bottom: 1px solid #dee2e6;
}

.modal-footer {
    padding: 10px 20px 15px 20px;
    border-top: 1px solid #dee2e6;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .icon-grid {
        grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
        gap: 8px;
    }
    
    .icon-item {
        padding: 8px;
    }
    
    .icon-item i {
        font-size: 1.5em !important;
    }
    
    .icon-name {
        font-size: 9px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedIcon = '{{ $selectedIcon ?? "file" }}';
    let selectedIconElement = null;
    
    // Initialize the icon picker
    function initializeIconPicker() {
        // Set initial selected icon
        updateSelectedIcon(selectedIcon);
        
        // Add click handlers to all icon items
        document.querySelectorAll('.icon-item').forEach(item => {
            item.addEventListener('click', function() {
                selectIcon(this);
            });
        });
        
        // Add search functionality
        document.getElementById('icon-search').addEventListener('input', function() {
            filterIcons(this.value);
        });
        
        // Add select button handler
        document.getElementById('select-icon-btn').addEventListener('click', function() {
            if (selectedIconElement) {
                updateSelectedIcon(selectedIconElement.dataset.icon);
                $('#iconPickerModal').modal('hide');
            }
        });
    }
    
    function selectIcon(element) {
        // Remove previous selection
        if (selectedIconElement) {
            selectedIconElement.classList.remove('selected');
        }
        
        // Add selection to new element
        element.classList.add('selected');
        selectedIconElement = element;
        
        // Enable select button
        document.getElementById('select-icon-btn').disabled = false;
    }
    
    function updateSelectedIcon(iconName) {
        selectedIcon = iconName;
        
        // Update hidden input
        document.getElementById('selected-icon-input').value = iconName;
        
        // Update preview
        document.getElementById('selected-icon-preview').className = 'fa fa-' + iconName + ' fa-2x';
        document.getElementById('selected-icon-name').textContent = iconName;
        
        // Update selection in modal if it's open
        const iconElement = document.querySelector(`[data-icon="${iconName}"]`);
        if (iconElement) {
            selectIcon(iconElement);
        }
    }
    
    function filterIcons(searchTerm) {
        const term = searchTerm.toLowerCase();
        document.querySelectorAll('.icon-item').forEach(item => {
            const iconName = item.dataset.icon.toLowerCase();
            const category = item.dataset.category;
            
            if (iconName.includes(term) || category.includes(term)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }
    
    // Initialize when modal is shown
    $('#iconPickerModal').on('shown.bs.modal', function() {
        // Reset search
        document.getElementById('icon-search').value = '';
        filterIcons('');
        
        // Reset selection
        if (selectedIconElement) {
            selectedIconElement.classList.remove('selected');
        }
        selectedIconElement = null;
        document.getElementById('select-icon-btn').disabled = true;
        
        // Ensure all icons are visible initially
        document.querySelectorAll('.icon-item').forEach(item => {
            item.style.display = 'flex';
        });
        
        // Force refresh of the active tab content
        const activeTab = document.querySelector('.tab-pane.active');
        if (activeTab) {
            activeTab.style.display = 'block';
        }
    });
    
    // Handle tab switching
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        // Ensure icons are visible when switching tabs
        const targetTab = $(e.target.getAttribute('href'));
        targetTab.find('.icon-item').show();
    });
    
    // Initialize the picker
    initializeIconPicker();
});
</script>
