/**
 * RMI (Risk Management Index) Assessment Script
 * Handles hierarchical interface with Dimensions > Sub-dimensions > Parameters
 * Author: RMI Assessment Team
 * Version: 2.0
 */

$(document).ready(function() {
    console.log('RMI Script: Initializing hierarchical interface...');
    
    // Create toast container if it doesn't exist
    if (!$('.toast-container').length) {
        $('body').append('<div class="toast-container"></div>');
    }
    
    // Initialize the RMI interface
    initRMIInterface();
    
    // Load saved progress from database
    loadSavedProgress();
    
    // Set up event handlers
    setupEventHandlers();
    
    // Initialize collapsible behavior
    initCollapsibleBehavior();
    
    // Initialize accordion state memory system
    initializeAccordionStateMemory();
    
    console.log('RMI Script: Initialization complete');
});

/**
 * Initialize the RMI interface
 */
function initRMIInterface() {
    // Update all progress bars and scores on page load
    updateAllProgress();
    
    // Set initial collapse icons
    updateCollapseIcons();
    
    // Initialize parameter phase badges
    updateParameterPhaseBadges();
}

/**
 * Set up all event handlers
 */
function setupEventHandlers() {
    // Checkbox change handlers
    $(document).on('change', '.rmi-checkbox', function() {
        handleCheckboxChange($(this));
    });
    
    // Phase check-all/uncheck-all handlers
    $(document).on('change', '.phase-check-all', function() {
        handlePhaseCheckAll($(this));
    });
    
    // Collapse event handlers for accordion state persistence
    $(document).on('show.bs.collapse', '.collapse', function() {
        updateCollapseIcon($(this), true);
        // Save accordion state when opening
        saveAccordionState(this.id, 'open');
    });
    
    $(document).on('hide.bs.collapse', '.collapse', function() {
        updateCollapseIcon($(this), false);
        // Save accordion state when closing
        saveAccordionState(this.id, 'close');
    });
    
    // Phase accordion handlers
    $(document).on('click', '.rmi-phase-header', function() {
        setTimeout(() => {
            updatePhaseToggleIcons();
        }, 300);
    });
}

/**
 * Handle checkbox state changes
 */
function handleCheckboxChange($checkbox) {
    const parameterKey = $checkbox.data('parameter');
    const phase = $checkbox.data('phase');
    const pointId = $checkbox.data('point');
    const pointText = $checkbox.data('point-text');
    const isCompleted = $checkbox.is(':checked');
    
    console.log('RMI Script: Checkbox changed', {
        parameter: parameterKey,
        phase: phase,
        point: pointId,
        completed: isCompleted
    });
    
    // Save progress to database - UI updates will happen in the success callback
    savePointProgress(parameterKey, phase, pointId, pointText, isCompleted, $checkbox);
}

/**
 * Handle phase check-all/uncheck-all checkbox changes
 */
function handlePhaseCheckAll($masterCheckbox) {
    const parameterKey = $masterCheckbox.data('parameter-key');
    const phaseLevel = $masterCheckbox.data('phase-level');
    const isChecked = $masterCheckbox.is(':checked');
    
    console.log('RMI Script: Phase check-all triggered', {
        parameter: parameterKey,
        phase: phaseLevel,
        checked: isChecked
    });
    
    // Find all child checkboxes in this specific phase
    const $phaseContainer = $('[data-parameter="' + parameterKey + '"] [data-phase="' + phaseLevel + '"]');
    const $childCheckboxes = $phaseContainer.find('.rmi-checkbox');
    
    if ($childCheckboxes.length === 0) {
        console.warn('RMI Script: No child checkboxes found for phase', phaseLevel, 'in parameter', parameterKey);
        return;
    }
    
    console.log('RMI Script: Found', $childCheckboxes.length, 'child checkboxes to update');
    
    // Update each child checkbox and trigger its change event for auto-save
    $childCheckboxes.each(function() {
        const $checkbox = $(this);
        const currentState = $checkbox.is(':checked');
        
        // Only change if the state is different to avoid unnecessary triggers
        if (currentState !== isChecked) {
            $checkbox.prop('checked', isChecked);
            // Trigger the change event to ensure auto-save and UI update
            $checkbox.trigger('change');
        }
    });
    
    // Show notification
    const action = isChecked ? 'checked' : 'unchecked';
    const phaseName = getPhaseName(phaseLevel);
    showNotification(
        `All items in ${phaseName} have been ${action}`,
        'success',
        'Phase Update'
    );
}

/**
 * Sync master checkbox state based on child checkboxes
 */
function syncMasterCheckbox(parameterKey, phaseLevel) {
    const $masterCheckbox = $('[data-parameter-key="' + parameterKey + '"][data-phase-level="' + phaseLevel + '"]');
    
    if (!$masterCheckbox.length) {
        return; // Master checkbox not found, nothing to sync
    }
    
    // Find all child checkboxes in this specific phase
    const $phaseContainer = $('[data-parameter="' + parameterKey + '"] [data-phase="' + phaseLevel + '"]');
    const $childCheckboxes = $phaseContainer.find('.rmi-checkbox');
    
    if ($childCheckboxes.length === 0) {
        return; // No child checkboxes found
    }
    
    const totalChildren = $childCheckboxes.length;
    const checkedChildren = $childCheckboxes.filter(':checked').length;
    
    // Update master checkbox state
    if (checkedChildren === 0) {
        // No children checked
        $masterCheckbox.prop('checked', false);
        $masterCheckbox.prop('indeterminate', false);
    } else if (checkedChildren === totalChildren) {
        // All children checked
        $masterCheckbox.prop('checked', true);
        $masterCheckbox.prop('indeterminate', false);
    } else {
        // Some children checked (partial state)
        $masterCheckbox.prop('checked', false);
        $masterCheckbox.prop('indeterminate', true);
    }
    
    console.log('RMI Script: Synced master checkbox', {
        parameter: parameterKey,
        phase: phaseLevel,
        checked: checkedChildren,
        total: totalChildren,
        state: checkedChildren === totalChildren ? 'all' : (checkedChildren === 0 ? 'none' : 'partial')
    });
}

/**
 * Get phase name by number
 */
function getPhaseName(phaseNumber) {
    const phaseNames = {
        1: 'Fase Awal',
        2: 'Fase Berkembang',
        3: 'Fase Praktik yang Baik',
        4: 'Fase Praktik yang Lebih Baik',
        5: 'Fase Praktik Terbaik'
    };
    return phaseNames[phaseNumber] || `Fase ${phaseNumber}`;
}

/**
 * Save point progress to database via AJAX
 */
function savePointProgress(parameterKey, phase, pointId, pointText, isCompleted, $checkbox) {
    // Ensure base_url is defined
    if (typeof base_url === 'undefined') {
        base_url = window.location.origin + window.location.pathname.split('/').slice(0, -1).join('/') + '/';
    }
    
    // Find dimension and subdimension from DOM before AJAX call
    let dimension = null;
    let subDimension = null;
    
    if ($checkbox && $checkbox.length) {
        const $parameterCard = $checkbox.closest('.rmi-parameter-card');
        const $subDimensionCard = $parameterCard.closest('.rmi-subdimension-card');
        if ($subDimensionCard.length) {
            dimension = $subDimensionCard.data('dimension');
            subDimension = $subDimensionCard.data('subdimension');
        }
    }
    
    const saveData = {
        parameter_key: parameterKey,
        phase_level: phase,
        point_identifier: pointId,
        point_text: pointText,
        is_completed: isCompleted ? 1 : 0
    };
    
    console.log('RMI Script: Saving progress with data:', saveData);
    console.log('RMI Script: Found dimension/subdimension:', { dimension, subDimension });
    
    $.ajax({
        url: base_url + 'rmi/save_point_progress',
        type: 'POST',
        dataType: 'json',
        data: saveData,
        beforeSend: function(xhr) {
            // Ensure AJAX header is set for CodeIgniter
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response) {
            console.log('RMI Script: AJAX response received:', response);
            
            if (response.status === 'success') {
                console.log('RMI Script: Progress saved successfully - triggering UI updates');
                showNotification('Progress saved successfully', 'success');
                
                // THIS IS THE NEW UPDATE LOGIC - Single source of truth for UI updates
                // Update parameter progress
                updateParameterProgress(parameterKey);
                
                // Update phase progress
                updatePhaseProgress(parameterKey, phase);
                
                // Update sub-dimension and dimension progress if we have the data
                if (dimension && subDimension) {
                    updateSubDimensionProgress(dimension, subDimension);
                    updateDimensionProgress(dimension);
                }
                
                // Sync master checkbox state for this phase
                syncMasterCheckbox(parameterKey, phase);
                
                console.log('RMI Script: All UI updates completed');
            } else {
                console.error('RMI Script: Server returned error:', response);
                showNotification('Error: ' + (response.message || 'Unknown server error'), 'error');
                
                // Revert checkbox state on error
                if ($checkbox && $checkbox.length) {
                    $checkbox.prop('checked', !isCompleted);
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('RMI Script: AJAX error details:', {
                status: status,
                error: error,
                responseText: xhr.responseText,
                statusCode: xhr.status,
                url: base_url + 'rmi/save_point_progress'
            });
            
            let errorMessage = 'Error saving progress. ';
            
            if (xhr.status === 404) {
                errorMessage += 'Page not found (404). Check URL routing.';
            } else if (xhr.status === 500) {
                errorMessage += 'Server error (500). Check logs.';
            } else if (xhr.status === 0) {
                errorMessage += 'Network error. Check connection.';
            } else {
                errorMessage += 'Please try again. (Status: ' + xhr.status + ')';
            }
              showNotification(errorMessage, 'error');
            
            // Revert checkbox state on error
            if ($checkbox && $checkbox.length) {
                $checkbox.prop('checked', !isCompleted);
                console.log('RMI Script: Reverted checkbox state due to save error');
            }
            
            // Try to parse and log error response
            try {
                const errorResponse = JSON.parse(xhr.responseText);
                console.error('RMI Script: Parsed error response:', errorResponse);
                if (errorResponse.message) {
                    showNotification('Server error: ' + errorResponse.message, 'error');
                }
            } catch (e) {
                console.error('RMI Script: Could not parse error response as JSON');
            }
        }
    });
}

/**
 * Update parameter progress display
 */
function updateParameterProgress(parameterKey) {
    const $parameterCard = $('.rmi-parameter-card[data-parameter="' + parameterKey + '"]');
    if (!$parameterCard.length) {
        console.warn('RMI Script: Parameter card not found for key:', parameterKey);
        return;
    }
    
    const totalPoints = parseInt($parameterCard.data('total-points'), 10) || 0;
    const completedCheckboxes = $parameterCard.find('.rmi-checkbox:checked').length;
    
    // Calculate progress percentage
    const progressPercent = totalPoints > 0 ? Math.round((completedCheckboxes / totalPoints) * 100) : 0;
    
    // Update progress bar - be more specific about targeting
    const $progressBar = $parameterCard.find('.progress-bar[data-parameter="' + parameterKey + '"]');
    if ($progressBar.length) {
        $progressBar.css('width', progressPercent + '%').attr('aria-valuenow', progressPercent);
    } else {
        console.warn('RMI Script: Progress bar not found for parameter:', parameterKey);
    }
    
    // Update progress text - be more specific about targeting
    const $progressText = $parameterCard.find('.progress-text[data-parameter="' + parameterKey + '"]');
    if ($progressText.length) {
        $progressText.text(completedCheckboxes + '/' + totalPoints + ' (' + progressPercent + '%)');
    } else {
        console.warn('RMI Script: Progress text not found for parameter:', parameterKey);
    }
    
    // Calculate and update parameter score using new phase-based logic
    const score = calculateScoreByPhaseCompletion(parameterKey);
    const $scoreValue = $parameterCard.find('.score-value[data-parameter="' + parameterKey + '"]');
    if ($scoreValue.length) {
        $scoreValue.text(score);
    } else {
        console.warn('RMI Script: Score value not found for parameter:', parameterKey);
    }
    
    // Update parameter phase badge
    updateParameterPhaseBadge(parameterKey);
    
    console.log('RMI Script: Updated parameter progress', {
        parameter: parameterKey,
        completed: completedCheckboxes,
        total: totalPoints,
        percent: progressPercent,
        score: score
    });
}

/**
 * Update parameter progress display with server data
 */
function updateParameterProgressDisplay(parameterKey, progressData) {
    const $parameterCard = $('.rmi-parameter-card[data-parameter="' + parameterKey + '"]');
    if (!$parameterCard.length) {
        console.warn('RMI Script: Parameter card not found for progress display update:', parameterKey);
        return;
    }
    
    // Update progress bar
    const $progressBar = $parameterCard.find('.progress-bar[data-parameter="' + parameterKey + '"]');
    if ($progressBar.length) {
        $progressBar.css('width', progressData.progress_percent + '%').attr('aria-valuenow', progressData.progress_percent);
    }
    
    // Update progress text
    const $progressText = $parameterCard.find('.progress-text[data-parameter="' + parameterKey + '"]');
    if ($progressText.length) {
        $progressText.text(progressData.completed_count + '/' + progressData.total_count + ' (' + progressData.progress_percent + '%)');
    }
    
    // Update parameter score
    const $scoreValue = $parameterCard.find('.score-value[data-parameter="' + parameterKey + '"]');
    if ($scoreValue.length) {
        $scoreValue.text(progressData.score);
    }
}

/**
 * Calculate parameter score based on phase completion sequence
 * Score is based on the highest consecutively completed phase
 */
function calculateScoreByPhaseCompletion(parameterKey) {
    const $parameterCard = $('[data-parameter="' + parameterKey + '"]');
    if (!$parameterCard.length) return 0;
    
    let score = 0;
    
    // Check each phase from 1 to 5 in sequence
    for (let phase = 1; phase <= 5; phase++) {
        const $phaseContainer = $parameterCard.find('[data-phase="' + phase + '"]');
        const totalCheckboxes = $phaseContainer.find('.rmi-checkbox').length;
        const completedCheckboxes = $phaseContainer.find('.rmi-checkbox:checked').length;
        
        // If this phase is complete (all checkboxes checked), increment score
        if (totalCheckboxes > 0 && completedCheckboxes === totalCheckboxes) {
            score = phase;
        } else {
            // If this phase is incomplete, stop checking further phases
            break;
        }
    }
    
    console.log('RMI Script: Phase-based score for parameter', parameterKey, '=', score);
    return score;
}

/**
 * Calculate parameter score based on progress percentage (legacy method)
 */
function calculateParameterScore(progressPercent) {
    if (progressPercent >= 90) return 5;
    if (progressPercent >= 70) return 4;
    if (progressPercent >= 50) return 3;
    if (progressPercent >= 30) return 2;
    if (progressPercent > 0) return 1;
    return 0;
}

/**
 * Update phase progress within a parameter
 */
function updatePhaseProgress(parameterKey, phase) {
    const $phaseContainer = $('[data-parameter="' + parameterKey + '"] [data-phase="' + phase + '"]');
    if (!$phaseContainer.length) return;
    
    const totalCheckboxes = $phaseContainer.find('.rmi-checkbox').length;
    const completedCheckboxes = $phaseContainer.find('.rmi-checkbox:checked').length;
    
    // Update phase progress text
    const $phaseProgress = $phaseContainer.find('.phase-progress');
    $phaseProgress.find('.completed-count').text(completedCheckboxes);
    $phaseProgress.find('.total-count').text(totalCheckboxes);
    
    // Show check icon if phase is complete
    const $checkIcon = $phaseContainer.find('.phase-check-icon');
    if (completedCheckboxes === totalCheckboxes && totalCheckboxes > 0) {
        $checkIcon.show();
    } else {
        $checkIcon.hide();
    }
}

/**
 * Update sub-dimension progress
 */
function updateSubDimensionProgress(dimension, subDimension) {
    const $subDimensionCard = $('[data-dimension="' + dimension + '"][data-subdimension="' + subDimension + '"]');
    if (!$subDimensionCard.length) return;
    
    // Get all parameters in this sub-dimension
    const $parameters = $subDimensionCard.find('.rmi-parameter-card');
    let totalCheckboxes = 0;
    let completedCheckboxes = 0;
    let totalScore = 0;
    
    $parameters.each(function() {
        const $param = $(this);
        const parameterKey = $param.data('parameter');
        const paramTotal = parseInt($param.data('total-points')) || 0;
        const paramCompleted = $param.find('.rmi-checkbox:checked').length;
        
        totalCheckboxes += paramTotal;
        completedCheckboxes += paramCompleted;
        
        // Add parameter score using new phase-based logic
        totalScore += calculateScoreByPhaseCompletion(parameterKey);
    });
    
    // Calculate sub-dimension progress
    const progressPercent = totalCheckboxes > 0 ? Math.round((completedCheckboxes / totalCheckboxes) * 100) : 0;
    const averageScore = $parameters.length > 0 ? (totalScore / $parameters.length).toFixed(1) : 0;
    
    // Update sub-dimension progress bar
    const $progressBar = $subDimensionCard.find('.subdimension-progress .progress-bar');
    $progressBar.css('width', progressPercent + '%').attr('aria-valuenow', progressPercent);
    
    // Update sub-dimension progress text
    const $progressText = $subDimensionCard.find('.subdimension-progress-text');
    $progressText.text(progressPercent + '%');
    
    // Update sub-dimension score
    const $scoreValue = $subDimensionCard.find('.subdimension-score');
    $scoreValue.text(averageScore);
    
    console.log('RMI Script: Updated sub-dimension progress', {
        dimension: dimension,
        subDimension: subDimension,
        completed: completedCheckboxes,
        total: totalCheckboxes,
        percent: progressPercent,
        score: averageScore
    });
}

/**
 * Update dimension progress
 */
function updateDimensionProgress(dimension) {
    const $dimensionCard = $('[data-dimension="' + dimension + '"]').first();
    if (!$dimensionCard.length) return;
    
    // Get all sub-dimensions in this dimension
    const $subDimensions = $dimensionCard.find('.rmi-subdimension-card');
    let totalCheckboxes = 0;
    let completedCheckboxes = 0;
    let totalScore = 0;
    
    $subDimensions.each(function() {
        const $subDim = $(this);
        
        // Count checkboxes in this sub-dimension
        const $parameters = $subDim.find('.rmi-parameter-card');
        $parameters.each(function() {
            const $param = $(this);
            const parameterKey = $param.data('parameter');
            const paramTotal = parseInt($param.data('total-points')) || 0;
            const paramCompleted = $param.find('.rmi-checkbox:checked').length;
            
            totalCheckboxes += paramTotal;
            completedCheckboxes += paramCompleted;
            
            // Add parameter score using new phase-based logic
            totalScore += calculateScoreByPhaseCompletion(parameterKey);
        });
    });
    
    // Calculate dimension progress
    const progressPercent = totalCheckboxes > 0 ? Math.round((completedCheckboxes / totalCheckboxes) * 100) : 0;
    const parameterCount = $dimensionCard.find('.rmi-parameter-card').length;
    const averageScore = parameterCount > 0 ? (totalScore / parameterCount).toFixed(1) : 0;
    
    // Update dimension progress bar
    const $progressBar = $dimensionCard.find('.dimension-progress .progress-bar');
    $progressBar.css('width', progressPercent + '%').attr('aria-valuenow', progressPercent);
    
    // Update dimension progress text
    const $progressText = $dimensionCard.find('.dimension-progress-text');
    $progressText.text(progressPercent + '%');
    
    // Update dimension score
    const $scoreValue = $dimensionCard.find('.dimension-score');
    $scoreValue.text(averageScore);
    
    console.log('RMI Script: Updated dimension progress', {
        dimension: dimension,
        completed: completedCheckboxes,
        total: totalCheckboxes,
        percent: progressPercent,
        score: averageScore
    });
}

/**
 * Update all progress on page load
 */
function updateAllProgress() {
    console.log('RMI Script: Updating all progress indicators...');
      // Update all parameter progress first
    $('.rmi-parameter-card').each(function() {
        const parameterKey = $(this).data('parameter');
        if (parameterKey) {
            updateParameterProgress(parameterKey);
            
            // Explicitly update each phase on page load to ensure UI is correctly initialized
            for (let phase = 1; phase <= 5; phase++) {
                updatePhaseProgress(parameterKey, phase);
                // Sync master checkbox state for each phase
                syncMasterCheckbox(parameterKey, phase);
            }
        }
    });
    
    // Update all sub-dimension progress
    $('.rmi-subdimension-card').each(function() {
        const dimension = $(this).data('dimension');
        const subDimension = $(this).data('subdimension');
        if (dimension && subDimension) {
            updateSubDimensionProgress(dimension, subDimension);
        }
    });
    
    // Update all dimension progress
    $('.rmi-dimension-card').each(function() {
        const dimension = $(this).data('dimension');
        if (dimension) {
            updateDimensionProgress(dimension);
        }
    });
}

/**
 * Initialize collapsible behavior
 */
function initCollapsibleBehavior() {
    // Ensure all collapses start closed except the first one
    $('.rmi-dimensions-level .collapse').removeClass('show');
    $('.rmi-subdimensions-level .collapse').removeClass('show');
    $('.rmi-parameters-level .collapse').removeClass('show');
    
    // Optionally show the first dimension by default
    // $('.rmi-dimension-card').first().find('.collapse').first().addClass('show');
}

/**
 * Update collapse icons
 */
function updateCollapseIcons() {
    $('.collapse').each(function() {
        const $collapse = $(this);
        const isShown = $collapse.hasClass('show');
        updateCollapseIcon($collapse, isShown);
    });
}

/**
 * Update a single collapse icon
 */
function updateCollapseIcon($collapse, isShown) {
    const $header = $('[data-target="#' + $collapse.attr('id') + '"]');
    const $icon = $header.find('.collapse-icon');
    
    if (isShown) {
        $icon.removeClass('fa-chevron-right').addClass('fa-chevron-down');
    } else {
        $icon.removeClass('fa-chevron-down').addClass('fa-chevron-right');
    }
}

/**
 * Update phase toggle icons
 */
function updatePhaseToggleIcons() {
    $('.rmi-phase-header').each(function() {
        const $header = $(this);
        const $icon = $header.find('.toggle-icon');
        const isExpanded = $header.attr('aria-expanded') === 'true';
        
        if (isExpanded) {
            $icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
        } else {
            $icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
        }
    });
}

/**
 * Update parameter phase badges
 */
function updateParameterPhaseBadges() {
    $('.rmi-parameter-card').each(function() {
        const parameterKey = $(this).data('parameter');
        updateParameterPhaseBadge(parameterKey);
    });
}

/**
 * Update individual parameter phase badge
 */
function updateParameterPhaseBadge(parameterKey) {
    const $parameterCard = $('[data-parameter="' + parameterKey + '"]');
    const $badge = $parameterCard.find('.parameter-phase-badge');
    
    // Use the new phase-based scoring logic
    const highestPhase = calculateScoreByPhaseCompletion(parameterKey);
    
    // Update badge
    $badge.text('Fase ' + highestPhase);
    
    // Update badge color based on phase
    $badge.removeClass('badge-secondary badge-info badge-warning badge-success badge-primary badge-dark');
    switch (highestPhase) {
        case 0:
            $badge.addClass('badge-secondary');
            break;
        case 1:
            $badge.addClass('badge-info');
            break;
        case 2:
            $badge.addClass('badge-warning');
            break;
        case 3:
            $badge.addClass('badge-success');
            break;
        case 4:
            $badge.addClass('badge-primary');
            break;
        case 5:
            $badge.addClass('badge-dark');
            break;
    }
}

/**
 * Load saved progress from database
 */
function loadSavedProgress() {
    $.ajax({
        url: base_url + 'rmi/get_saved_progress',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success' && response.data) {
                console.log('RMI Script: Loading saved progress...');
                
                // Apply saved progress to checkboxes
                response.data.forEach(function(item) {
                    if (item.is_completed) {
                        const checkboxId = item.parameter_key + '_' + item.phase_level + '_' + item.point_identifier;
                        const $checkbox = $('#' + checkboxId);
                        if ($checkbox.length) {
                            $checkbox.prop('checked', true);
                        }
                    }
                });
                
                // Update all progress after loading
                setTimeout(() => {
                    updateAllProgress();
                }, 100);
            }
        },
        error: function(xhr, status, error) {
            console.warn('RMI Script: Could not load saved progress:', error);
        }
    });
}

/**
 * Show toast notification to user
 */
function showNotification(message, type = 'info', title = '') {
    // Set default titles based on type
    if (!title) {
        switch (type) {
            case 'success':
                title = 'Success';
                break;
            case 'error':
                title = 'Error';
                break;
            case 'warning':
                title = 'Warning';
                break;
            case 'info':
            default:
                title = 'Info';
                break;
        }
    }
    
    // Create unique ID for this toast
    const toastId = 'toast-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
    
    // Choose appropriate icon
    let icon = '';
    switch (type) {
        case 'success':
            icon = '<i class="fas fa-check-circle toast-icon"></i>';
            break;
        case 'error':
            icon = '<i class="fas fa-exclamation-circle toast-icon"></i>';
            break;
        case 'warning':
            icon = '<i class="fas fa-exclamation-triangle toast-icon"></i>';
            break;
        case 'info':
        default:
            icon = '<i class="fas fa-info-circle toast-icon"></i>';
            break;
    }
    
    // Create toast element
    const toast = $(`
        <div class="toast-message ${type}" id="${toastId}">
            <div class="toast-content">
                <div class="toast-title">
                    ${icon} ${title}
                </div>
                <div class="toast-body">${message}</div>
            </div>
            <button class="toast-close" type="button" aria-label="Close">
                &times;
            </button>
        </div>
    `);
    
    // Add close button handler
    toast.find('.toast-close').on('click', function() {
        removeToast(toast);
    });
    
    // Add to container
    $('.toast-container').append(toast);
    
    // Show with animation
    setTimeout(() => {
        toast.addClass('show');
    }, 100);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        removeToast(toast);
    }, 5000);
    
    return toast;
}

/**
 * Remove toast with animation
 */
function removeToast($toast) {
    $toast.addClass('removing');
    setTimeout(() => {
        $toast.remove();
    }, 300);
}

/**
 * Initialize accordion state memory system
 * Called on page load to set up the state management
 */
function initializeAccordionStateMemory() {
    console.log('RMI Script: Initializing accordion state memory system...');
    
    // Clean up any invalid accordion states first
    cleanupAccordionState();
    
    // Restore previously saved accordion states
    restoreAccordionState();
    
    // Set up mutation observer to track dynamic content changes
    setupAccordionMutationObserver();
    
    console.log('RMI Script: Accordion state memory initialized');
}

/**
 * Save accordion state to localStorage with improved reliability
 * @param {string} elementId - The ID of the accordion element
 * @param {string} state - Either 'open' or 'close'
 */
function saveAccordionState(elementId, state) {
    // Validate input parameters
    if (!elementId || typeof elementId !== 'string') {
        console.warn('RMI Script: saveAccordionState called with invalid elementId:', elementId);
        return;
    }
    
    if (state !== 'open' && state !== 'close') {
        console.warn('RMI Script: Invalid state provided to saveAccordionState. Expected "open" or "close", got:', state);
        return;
    }
    
    // Check if localStorage is available
    if (!isLocalStorageAvailable()) {
        console.warn('RMI Script: localStorage not available, cannot save accordion state');
        return;
    }
    
    try {
        // Get current array of open accordion IDs from localStorage
        let openAccordions = getOpenAccordionsFromStorage();
        
        if (state === 'open') {
            // Add the elementId to the array, ensuring no duplicates
            if (!openAccordions.includes(elementId)) {
                openAccordions.push(elementId);
                console.log('RMI Script: Added accordion to open state:', elementId);
            }
        } else if (state === 'close') {
            // Remove the elementId from the array
            const initialLength = openAccordions.length;
            openAccordions = openAccordions.filter(id => id !== elementId);
            
            if (openAccordions.length < initialLength) {
                console.log('RMI Script: Removed accordion from open state:', elementId);
            }
        }
        
        // Save the updated array back to localStorage as JSON string
        setOpenAccordionsToStorage(openAccordions);
        
        console.log('RMI Script: Accordion state saved successfully', { 
            elementId, 
            state, 
            totalOpenAccordions: openAccordions.length
        });
        
    } catch (e) {
        console.error('RMI Script: Error saving accordion state to localStorage:', e);
    }
}

/**
 * Restore accordion state from localStorage with enhanced error handling
 * Reads saved state and programmatically opens previously opened accordions
 */
function restoreAccordionState() {
    if (!isLocalStorageAvailable()) {
        console.warn('RMI Script: localStorage not available, cannot restore accordion state');
        return;
    }
    
    try {
        // Read the array of IDs from localStorage
        const openAccordions = getOpenAccordionsFromStorage();
        
        // Check if array exists and is not empty
        if (openAccordions.length > 0) {
            console.log('RMI Script: Restoring accordion state for:', openAccordions);
            
            // Use timeout to ensure DOM is fully ready and avoid Bootstrap timing issues
            setTimeout(() => {
                let restoredCount = 0;
                let notFoundCount = 0;
                
                // Loop through each saved elementId
                openAccordions.forEach(elementId => {
                    // Find the corresponding DOM element
                    const $accordionElement = $('#' + elementId);
                    
                    if ($accordionElement.length > 0) {
                        // Check if it's not already open to avoid unnecessary operations
                        if (!$accordionElement.hasClass('show')) {
                            // Use Bootstrap's jQuery method to open it
                            $accordionElement.collapse('show');
                            restoredCount++;
                            console.log('RMI Script: Restored accordion:', elementId);
                        } else {
                            restoredCount++;
                            console.log('RMI Script: Accordion already open:', elementId);
                        }
                    } else {
                        notFoundCount++;
                        console.warn('RMI Script: Could not find accordion element with ID:', elementId);
                    }
                });
                
                console.log('RMI Script: Accordion restoration complete', {
                    requested: openAccordions.length,
                    restored: restoredCount,
                    notFound: notFoundCount
                });
                
                // Clean up any missing accordion IDs if some weren't found
                if (notFoundCount > 0) {
                    cleanupAccordionState();
                }
                
            }, 500); // Increased timeout to ensure proper Bootstrap initialization
        } else {
            console.log('RMI Script: No accordion states to restore');
        }
        
    } catch (e) {
        console.error('RMI Script: Error restoring accordion state from localStorage:', e);
        // Try to clear corrupted data
        clearAccordionState();
    }
}

/**
 * Clean up accordion state by removing IDs that no longer exist in the DOM
 */
function cleanupAccordionState() {
    if (!isLocalStorageAvailable()) return;
    
    try {
        const openAccordions = getOpenAccordionsFromStorage();
        const validAccordions = openAccordions.filter(elementId => {
            return $('#' + elementId).length > 0;
        });
        
        if (validAccordions.length !== openAccordions.length) {
            setOpenAccordionsToStorage(validAccordions);
            console.log('RMI Script: Cleaned up accordion state', {
                before: openAccordions.length,
                after: validAccordions.length,
                removed: openAccordions.length - validAccordions.length
            });
        }
    } catch (e) {
        console.error('RMI Script: Error cleaning up accordion state:', e);
    }
}

/**
 * Set up mutation observer to handle dynamically added accordions
 */
function setupAccordionMutationObserver() {
    // Only set up if MutationObserver is available
    if (typeof MutationObserver === 'undefined') {
        console.warn('RMI Script: MutationObserver not available, dynamic accordion tracking disabled');
        return;
    }
    
    const observer = new MutationObserver(function(mutations) {
        let hasNewAccordions = false;
        
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                // Check if any added nodes contain accordion elements
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        const $node = $(node);
                        if ($node.hasClass('collapse') || $node.find('.collapse').length > 0) {
                            hasNewAccordions = true;
                        }
                    }
                });
            }
        });
        
        // If new accordions were added, restore their state
        if (hasNewAccordions) {
            console.log('RMI Script: New accordions detected, restoring state...');
            setTimeout(() => {
                restoreAccordionState();
            }, 100);
        }
    });
    
    // Start observing
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    console.log('RMI Script: Accordion mutation observer set up');
}

/**
 * Helper function to get open accordions from localStorage safely
 */
function getOpenAccordionsFromStorage() {
    try {
        const stored = localStorage.getItem('rmiOpenAccordions');
        const parsed = JSON.parse(stored || '[]');
        return Array.isArray(parsed) ? parsed : [];
    } catch (e) {
        console.warn('RMI Script: Could not parse accordion state from localStorage:', e);
        return [];
    }
}

/**
 * Helper function to set open accordions to localStorage safely
 */
function setOpenAccordionsToStorage(accordions) {
    try {
        if (!Array.isArray(accordions)) {
            console.warn('RMI Script: Attempted to save non-array accordion state:', accordions);
            return;
        }
        localStorage.setItem('rmiOpenAccordions', JSON.stringify(accordions));
    } catch (e) {
        console.error('RMI Script: Could not save accordion state to localStorage:', e);
    }
}

/**
 * Check if localStorage is available
 */
function isLocalStorageAvailable() {
    try {
        const test = '__localStorage_test__';
        localStorage.setItem(test, test);
        localStorage.removeItem(test);
        return true;
    } catch (e) {
        return false;
    }
}

/**
 * Clear accordion state from localStorage (for debugging or reset)
 */
function clearAccordionState() {
    if (!isLocalStorageAvailable()) {
        console.warn('RMI Script: localStorage not available, cannot clear accordion state');
        return;
    }
    
    try {
        localStorage.removeItem('rmiOpenAccordions');
        console.log('RMI Script: Cleared accordion state from localStorage');
        
        // Also close all currently open accordions
        $('.collapse.show').each(function() {
            $(this).collapse('hide');
        });
        
        showNotification('Accordion state cleared and all accordions closed', 'info', 'State Reset');
    } catch (e) {
        console.error('RMI Script: Error clearing accordion state:', e);
    }
}

/**
 * Get current accordion state for debugging
 */
function getAccordionStateDebugInfo() {
    const info = {
        localStorageAvailable: isLocalStorageAvailable(),
        storedAccordions: getOpenAccordionsFromStorage(),
        currentlyOpenInDOM: [],
        accordionElements: []
    };
    
    // Check which accordions are currently open in the DOM
    $('.collapse.show').each(function() {
        info.currentlyOpenInDOM.push(this.id);
    });
    
    // List all accordion elements
    $('.collapse').each(function() {
        info.accordionElements.push({
            id: this.id,
            isOpen: $(this).hasClass('show'),
            exists: true
        });
    });
    
    return info;
}

/**
 * Debug function to test AJAX connectivity and data format
 */
function debugAjaxSave() {
    console.log('RMI Debug: Testing AJAX save functionality...');
    console.log('RMI Debug: Current base_url:', base_url);
    
    // Test database debug endpoint first
    $.ajax({
        url: base_url + 'rmi/debug_db',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('RMI Debug: Database debug response:', response);
            showNotification('Database debug completed. Check console for details.', 'info', 'Debug');
        },
        error: function(xhr, status, error) {
            console.error('RMI Debug: Database debug failed:', error);
            console.error('Response:', xhr.responseText);
            showNotification('Database debug failed: ' + error, 'error', 'Debug Error');
        }
    });
    
    // Test a simple save operation using the actual save function
    const testData = {
        parameter_key: 'debug_test_' + Date.now(),
        phase_level: 1,
        point_identifier: 'debug_point',
        point_text: 'Debug test point',
        is_completed: 1
    };
    
    console.log('RMI Debug: Testing save with actual savePointProgress function...');
      // Use the actual save function that's used by checkboxes
    savePointProgress(
        testData.parameter_key,
        testData.phase_level,
        testData.point_identifier,
        testData.point_text,
        true,
        null // No checkbox element for debug test
    );
    
    // Also test direct AJAX call
    setTimeout(() => {
        console.log('RMI Debug: Testing direct AJAX call...');
        $.ajax({
            url: base_url + 'rmi/save_point_progress',
            type: 'POST',
            dataType: 'json',
            data: testData,
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            },
            success: function(response) {
                console.log('RMI Debug: Direct AJAX response:', response);
                if (response.status === 'success') {
                    showNotification('Direct AJAX test successful!', 'success', 'Debug Success');
                } else {
                    showNotification('Direct AJAX failed: ' + response.message, 'error', 'Debug Error');
                }
            },
            error: function(xhr, status, error) {
                console.error('RMI Debug: Direct AJAX failed:', {
                    status: status,
                    error: error,
                    responseText: xhr.responseText,
                    statusCode: xhr.status
                });
                showNotification('Direct AJAX test failed: ' + error, 'error', 'Debug Error');
            }
        });
    }, 1000);
}

/**
 * Utility function to get base URL (should be defined in the main layout)
 */
if (typeof base_url === 'undefined') {
    var base_url = window.location.origin + '/';
}

// Export functions for testing or external use
window.RMI = {
    updateParameterProgress: updateParameterProgress,
    updateSubDimensionProgress: updateSubDimensionProgress,
    updateDimensionProgress: updateDimensionProgress,
    updateAllProgress: updateAllProgress,
    calculateParameterScore: calculateParameterScore,
    calculateScoreByPhaseCompletion: calculateScoreByPhaseCompletion,
    showNotification: showNotification,
    initializeAccordionStateMemory: initializeAccordionStateMemory,
    saveAccordionState: saveAccordionState,
    restoreAccordionState: restoreAccordionState,
    clearAccordionState: clearAccordionState,
    cleanupAccordionState: cleanupAccordionState,
    getAccordionStateDebugInfo: getAccordionStateDebugInfo,
    handlePhaseCheckAll: handlePhaseCheckAll,
    syncMasterCheckbox: syncMasterCheckbox,
    getPhaseName: getPhaseName,
    debugAjaxSave: debugAjaxSave
};
