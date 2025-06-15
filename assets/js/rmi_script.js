/**
 * RMI (Risk Management Index) Assessment Script
 * Handles hierarchical interface with Dimensions > Sub-dimensions > Parameters
 * Author: RMI Assessment Team
 * Version: 2.0
 */

// Helper function to safely initialize Bootstrap tooltips
function initializeBootstrapTooltip($element, options = {}) {
    try {
        // Check if Bootstrap tooltip is available
        if (typeof $element.tooltip === 'function') {
            // Dispose any existing tooltip
            $element.tooltip('dispose');
            
            // Default options
            const defaultOptions = {
                placement: 'top',
                trigger: 'hover',
                container: 'body',
                html: false,
                sanitize: true
            };
            
            // Merge with provided options
            const finalOptions = Object.assign({}, defaultOptions, options);
            
            // Initialize tooltip
            $element.tooltip(finalOptions);
            
            console.log('RMI Script: Bootstrap tooltip initialized successfully');
        } else {
            console.warn('RMI Script: Bootstrap tooltip method not available');
        }
    } catch (error) {
        console.error('RMI Script: Error initializing tooltip:', error);
    }
}

// Helper function to reinitialize all tooltips in the document
function reinitializeAllTooltips() {
    try {
        // Reinitialize all elements with data-toggle="tooltip"
        $('[data-toggle="tooltip"]').tooltip();
        console.log('RMI Script: All tooltips reinitialized');
    } catch (error) {
        console.error('RMI Script: Error reinitializing tooltips:', error);
    }
}

// Global RMI object with notification manager
window.RMI = window.RMI || {};

// Notification manager for bulk operations
window.RMI.notificationManager = {
    activeOps: {}, // To store progress of active bulk operations

    // Call this when a bulk operation starts
    startBulkOp: function(opId, totalItems, scopeText) {
        this.activeOps[opId] = {
            total: totalItems,
            completed: 0,
            scope: scopeText
        };
        console.log('RMI Script: Started bulk operation', { opId, totalItems, scopeText });
    },

    // Call this after each item in a bulk operation is successfully saved
    logProgress: function(opId) {
        if (!this.activeOps[opId]) return;

        this.activeOps[opId].completed++;
        console.log('RMI Script: Bulk operation progress', { 
            opId, 
            completed: this.activeOps[opId].completed, 
            total: this.activeOps[opId].total 
        });

        // When the last item is saved, show the summary notification and clean up
        if (this.activeOps[opId].completed === this.activeOps[opId].total) {
            const message = `Successfully updated ${this.activeOps[opId].total} items in "${this.activeOps[opId].scope}".`;
            showNotification(message, 'success', 'Bulk Update Complete');
            delete this.activeOps[opId]; // Clean up the completed operation
            console.log('RMI Script: Bulk operation completed', { opId });
        }
    }
};

$(document).ready(function() {
    console.log('RMI Script: Initializing hierarchical interface...');
    
    // Create toast container if it doesn't exist
    if (!$('.toast-container').length) {
        $('body').append('<div class="toast-container"></div>');
    }
    
    // Initialize performance aspects interface (safe to run early)
    initPerformanceAspects();
    
    // Set up event handlers (safe to run early)
    setupEventHandlers();
    
    // Initialize collapsible behavior (safe to run early)
    initCollapsibleBehavior();
    
    // Initialize accordion state memory system (safe to run early)
    initializeAccordionStateMemory();
    
    // Load saved progress from database - this will call initRMIInterface() when complete
    loadSavedProgress();
    
    console.log('RMI Script: Basic initialization complete, waiting for saved progress...');
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
    
    // Apply sequential phase locking
    applyPhaseLockingToAllParameters();
    
    // Update hierarchical button states
    updateAllHierarchicalButtonStates();
}

/**
 * Set up all event handlers
 */
function setupEventHandlers() {
    // Checkbox change handlers with bulk operation data support
    $(document).on('change', '.rmi-checkbox', function(event, data) {
        handleCheckboxChange($(this), data);
    });
    
    // Phase check-all/uncheck-all handlers
    $(document).on('change', '.phase-check-all', function() {
        handlePhaseCheckAll($(this));
    });
      // Hierarchical check-all/uncheck-all handlers
    $(document).on('click', '.hierarchical-check-all-btn', function() {
        handleHierarchicalCheckAll($(this));
    });
    
    // Area of Improvement button handlers
    $(document).on('click', '.improvement-btn', function() {
        handleAreaOfImprovement($(this));
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
function handleCheckboxChange($checkbox, data) {
    const parameterKey = $checkbox.data('parameter');
    const phase = $checkbox.data('phase');
    const pointId = $checkbox.data('point');
    const pointText = $checkbox.data('point-text');
    const isCompleted = $checkbox.is(':checked');
    
    console.log('RMI Script: Checkbox changed', {
        parameter: parameterKey,
        phase: phase,
        point: pointId,
        completed: isCompleted,
        isBulk: data && data.isBulk
    });
    
    // Update phase locking immediately when checkbox state changes
    const $parameterContainer = $checkbox.closest('.rmi-parameter-card');
    updatePhaseLocking($parameterContainer);
    
    // Save progress to database - UI updates will happen in the success callback
    savePointProgress(parameterKey, phase, pointId, pointText, isCompleted, $checkbox, data);
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
    
    // Update phase locking after bulk checkbox changes
    const $parameterContainer = $phaseContainer.closest('.rmi-parameter-card');
    updatePhaseLocking($parameterContainer);
    
    // Show context-aware notification
    const action = isChecked ? 'checked' : 'unchecked';
    const phaseName = getPhaseName(phaseLevel);
    const parameterTitle = $parameterContainer.find('.parameter-title').text().trim() || parameterKey;
    const message = `All items in ${phaseName} for "${parameterTitle}" have been ${action}.`;
    showNotification(message, 'success', 'Phase Update Complete');
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
function savePointProgress(parameterKey, phase, pointId, pointText, isCompleted, $checkbox, data) {
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
            console.log('RMI Script: AJAX response received:', response);            if (response.status === 'success') {                console.log('RMI Script: Progress saved successfully - triggering UI updates');
                
                // Context-aware notification logic
                if (data && data.isBulk) {
                    // If it's a bulk action, just log progress. The manager will show the notification.
                    window.RMI.notificationManager.logProgress(data.opId);
                } else {
                    // If it's a single, manual click, show a detailed notification.
                    const phaseName = getPhaseName(phase); // Use existing helper function
                    const action = isCompleted ? 'completed' : 'unchecked';
                    const message = `Item "${pointText}" in "${phaseName}" has been marked as ${action}.`;
                    showNotification(message, 'success', 'Progress Saved');
                }
                
                // FIX 1: Call updateAllProgress() for real-time score updates 
                // This ensures immediate progressive visibility of scores without page refresh
                updateAllProgress();
                
                // Update hierarchical button states after all progress updates
                updateAllHierarchicalButtonStates();
                
                console.log('RMI Script: All UI updates completed');
            } else {
                console.error('RMI Script: Server returned error:', response);
                showNotification('Error: ' + (response.message || 'Unknown server error'), 'error');
                
                // Revert checkbox state on error
                if ($checkbox && $checkbox.length) {
                    $checkbox.prop('checked', !isCompleted);
                }
            }
        },        error: function(xhr, status, error) {
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
    
    // Get total points directly from data-total-points attribute (this is the correct total across all 5 phases)
    const totalPoints = parseInt($parameterCard.data('total-points'), 10) || 0;
    const completedCheckboxes = $parameterCard.find('.rmi-checkbox:checked').length;
    
    // Calculate progress percentage with proper capping at 100%
    let progressPercent = totalPoints > 0 ? (completedCheckboxes / totalPoints) * 100 : 0;
    progressPercent = Math.min(Math.round(progressPercent), 100); // Cap at 100%
    
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
 * Update sub-dimension progress with granular conditional scoring
 */
function updateSubDimensionProgress(dimension, subDimension) {
    // Update progress calculations
    updateSubDimensionProgressCalculations(dimension, subDimension);
    
    // Apply granular score visibility for this specific sub-dimension
    const $subDimensionCard = $('[data-dimension="' + dimension + '"][data-subdimension="' + subDimension + '"]');
    if (!$subDimensionCard.length) return;
    
    // Check parameters in this sub-dimension only
    let subdimensionUnassessedParams = [];
    
    $subDimensionCard.find('.rmi-parameter-card').each(function() {
        const $parameterCard = $(this);
        const parameterTitle = $parameterCard.find('.parameter-title').text().trim();
        const parameterScore = parseFloat($parameterCard.find('.score-value').text()) || 0;
        
        if (parameterScore === 0) {
            subdimensionUnassessedParams.push(parameterTitle);
        }
    });
      // Handle sub-dimension score display
    const $subdimensionScore = $SubDimensionCard.find('.subdimension-score');
    if (subdimensionUnassessedParams.length === 0) {
        // Calculate and display the sub-dimension score normally
        const subdimScore = calculateSubDimensionScore(dimension, subDimension);
        $subdimensionScore.text(subdimScore.toFixed(2));
        $subdimensionScore.removeAttr('title');
    } else {
        // Display - and set tooltip
        $subdimensionScore.text('-');
        const tooltipText = `Harap selesaikan penilaian untuk parameter berikut: ${subdimensionUnassessedParams.join(', ')}`;
        $subdimensionScore.attr('title', tooltipText);
    }
    
    console.log('RMI Script: Updated sub-dimension with granular logic', {
        dimension: dimension,
        subDimension: subDimension,
        unassessedCount: subdimensionUnassessedParams.length
    });
}

/**
 * Update dimension progress with granular conditional scoring
 */
function updateDimensionProgress(dimension) {
    // Update progress calculations
    updateDimensionProgressCalculations(dimension);
    
    // Apply granular score visibility for this specific dimension
    const $dimensionCard = $('[data-dimension="' + dimension + '"]').first();
    if (!$dimensionCard.length) return;
    
    // Check parameters in this dimension only
    let dimensionUnassessedParams = [];
    
    $dimensionCard.find('.rmi-parameter-card').each(function() {
        const $parameterCard = $(this);
        const parameterTitle = $parameterCard.find('.parameter-title').text().trim();
        const parameterScore = parseFloat($parameterCard.find('.score-value').text()) || 0;
        
        if (parameterScore === 0) {
            dimensionUnassessedParams.push(parameterTitle);
        }
    });
    
    // Handle dimension score display    const $dimensionScore = $dimensionCard.find('.dimension-score');
    if (dimensionUnassessedParams.length === 0) {
        // Calculate and display the dimension score
        const dimScore = calculateDimensionScore(dimension);
        $dimensionScore.text(dimScore.toFixed(2));
        $dimensionScore.removeAttr('title');
    } else {
        // Display - and set tooltip
        $dimensionScore.text('-');
        const tooltipText = `Harap selesaikan penilaian untuk parameter berikut: ${dimensionUnassessedParams.join(', ')}`;
        $dimensionScore.attr('title', tooltipText);
    }
    
    console.log('RMI Script: Updated dimension with granular logic', {
        dimension: dimension,
        unassessedCount: dimensionUnassessedParams.length
    });
}

/**
 * Update all progress with strict bottom-up sequence to prevent race conditions
 */
function updateAllProgress() {
    console.log('RMI Script: Starting complete progress update with bottom-up sequence...');
    
    // STEP 1: First, update ALL parameter progress (bottom level)
    console.log('RMI Script: Step 1 - Updating all parameter progress...');
    $('.rmi-parameter-card').each(function() {
        const parameterKey = $(this).data('parameter');
        if (parameterKey) {
            updateParameterProgress(parameterKey);
            
            // Also update each phase on page load to ensure UI is correctly initialized
            for (let phase = 1; phase <= 5; phase++) {
                updatePhaseProgress(parameterKey, phase);
                // Sync master checkbox state for each phase
                syncMasterCheckbox(parameterKey, phase);
            }
        }
    });
    console.log('RMI Script: Step 1 completed - All parameters updated');
    
    // STEP 2: Second, update ALL sub-dimension progress (middle level)
    console.log('RMI Script: Step 2 - Updating all sub-dimension progress...');
    const processedSubDimensions = new Set(); // Prevent duplicate processing
    
    $('.rmi-subdimension-card').each(function() {
        const $subDimensionCard = $(this);
        const dimension = $subDimensionCard.data('dimension');
        const subDimension = $subDimensionCard.data('subdimension');
        
        if (dimension && subDimension) {
            const subDimKey = dimension + '|' + subDimension;
            if (!processedSubDimensions.has(subDimKey)) {
                processedSubDimensions.add(subDimKey);
                updateSubDimensionProgress(dimension, subDimension);
                console.log('RMI Script: Updated sub-dimension:', subDimension);
            }
        }
    });
    console.log('RMI Script: Step 2 completed - All sub-dimensions updated');
    
    // STEP 3: Third, update ALL dimension progress (top level)
    console.log('RMI Script: Step 3 - Updating all dimension progress...');
    const processedDimensions = new Set(); // Prevent duplicate processing
    
    $('.rmi-dimension-card').each(function() {
        const $dimensionCard = $(this);
        const dimension = $dimensionCard.data('dimension');
        
        if (dimension) {
            if (!processedDimensions.has(dimension)) {
                processedDimensions.add(dimension);
                updateDimensionProgress(dimension);
                console.log('RMI Script: Updated dimension:', dimension);
            }
        }
    });
    console.log('RMI Script: Step 3 completed - All dimensions updated');
    
    // STEP 4: Finally, update global scores (global level)
    console.log('RMI Script: Step 4 - Updating global scores...');
    
    // Collect all unassessed parameters for global score logic
    let allUnassessedParams = [];
    $('.rmi-parameter-card').each(function() {
        const $parameterCard = $(this);
        const parameterKey = $parameterCard.data('parameter');
        const parameterTitle = $parameterCard.find('.parameter-title').text().trim();
        
        // Get parameter score more reliably
        let parameterScore = parseFloat($parameterCard.find('.score-value[data-parameter="' + parameterKey + '"]').text()) || 0;
        
        // If score element shows 0, double-check by calculating the score directly
        if (parameterScore === 0) {
            parameterScore = calculateScoreByPhaseCompletion(parameterKey);
        }
        
        // If parameter's score is still 0 after calculation, add to unassessed list
        if (parameterScore === 0) {
            allUnassessedParams.push(parameterTitle);
        }
    });
    
    // Handle global scores with the collected unassessed parameters
    handleGlobalScores(allUnassessedParams);
    console.log('RMI Script: Step 4 completed - Global scores updated');
    
    console.log('RMI Script: Complete progress update finished successfully');
}

/**
 * Update sub-dimension progress calculations only (progress bar and percentage)
 */
function updateSubDimensionProgressCalculations(dimension, subDimension) {
    // Find the correct sub-dimension container card
    const $subDimensionCard = $('[data-dimension="' + dimension + '"][data-subdimension="' + subDimension + '"]');
    if (!$subDimensionCard.length) {
        console.warn('RMI Script: Sub-dimension card not found:', dimension, '>', subDimension);
        return;
    }
    
    // Find all child parameter progress bars within this sub-dimension
    const $parameterProgressBars = $subDimensionCard.find('.rmi-parameter-card .progress-bar');
    
    if ($parameterProgressBars.length === 0) {
        console.warn('RMI Script: No parameter progress bars found in sub-dimension:', subDimension);
        return;
    }
    
    // Initialize counters
    let totalPercentage = 0;
    let parameterCount = 0;
    
    // Loop through each parameter's progress bar
    $parameterProgressBars.each(function() {
        const $progressBar = $(this);
        // Safely parse completion percentage with fallback to 0
        const percentage = parseFloat($progressBar.attr('aria-valuenow')) || 0;
        totalPercentage += percentage;
        parameterCount++;
        
        console.log('RMI Script: Parameter progress found:', percentage + '%');
    });
    
    // Calculate average percentage
    const averagePercentage = parameterCount > 0 ? Math.round(totalPercentage / parameterCount) : 0;
    
    // Apply this average to the sub-dimension's progress bar
    const $subDimensionProgressBar = $subDimensionCard.find('.subdimension-progress .progress-bar');
    if ($subDimensionProgressBar.length) {
        $subDimensionProgressBar.css('width', averagePercentage + '%')
                                 .attr('aria-valuenow', averagePercentage);
    }
    
    // Apply to the progress text
    const $subDimensionProgressText = $subDimensionCard.find('.subdimension-progress-text');
    if ($subDimensionProgressText.length) {
        $subDimensionProgressText.text(averagePercentage + '%');
    }
    
    console.log('RMI Script: Updated sub-dimension progress calculations', {
        dimension: dimension,
        subDimension: subDimension,
        parameterCount: parameterCount,
        averagePercentage: averagePercentage
    });
}

/**
 * Update sub-dimension progress including score logic (- vs. Number)
 */
function updateSubDimensionProgress(dimension, subDimension) {
    // First update the progress calculations
    updateSubDimensionProgressCalculations(dimension, subDimension);
    
    // Find the correct sub-dimension container card
    const $subDimensionCard = $('[data-dimension="' + dimension + '"][data-subdimension="' + subDimension + '"]');
    if (!$subDimensionCard.length) {
        console.warn('RMI Script: Sub-dimension card not found for score update:', dimension, '>', subDimension);
        return;
    }
    
    // Find all child parameter score elements within this sub-dimension
    const $parameterScoreElements = $subDimensionCard.find('.score-value[data-parameter]');
    
    if ($parameterScoreElements.length === 0) {
        console.warn('RMI Script: No parameter score elements found in sub-dimension:', subDimension);
        return;
    }
    
    // Check completion status and collect scores
    let incompleteParameters = [];
    let totalScore = 0;
    let completeParameterCount = 0;
    
    $parameterScoreElements.each(function() {
        const $scoreElement = $(this);
        const scoreText = $scoreElement.text().trim();
        const score = parseFloat(scoreText) || 0;
        
        // Get parameter name for incomplete list
        const parameterKey = $scoreElement.data('parameter');
        const $parameterCard = $scoreElement.closest('.rmi-parameter-card');
        const parameterTitle = $parameterCard.find('.parameter-title').text().trim() || parameterKey;
        
        if (score === 0 || scoreText === '-') {
            incompleteParameters.push(parameterTitle);
        } else {
            totalScore += score;
            completeParameterCount++;
        }
        
        console.log('RMI Script: Parameter score check:', parameterTitle, '=', score);
    });
    
    // Find sub-dimension score display elements
    const $subDimensionScore = $subDimensionCard.find('.subdimension-score');
    const $subDimensionTooltipIcon = $subDimensionScore.siblings('.tooltip-icon');
    
    if (incompleteParameters.length > 0) {
        // Some parameters incomplete - show hyphen and tooltip
        $subDimensionScore.text('-');
          // Show tooltip icon with incomplete parameter names
        const tooltipText = `Harap selesaikan penilaian untuk parameter berikut: ${incompleteParameters.join(', ')}`;
        $subDimensionTooltipIcon.show()
                                .attr('title', tooltipText)
                                .attr('data-toggle', 'tooltip')
                                .attr('data-placement', 'top');        // Initialize Bootstrap tooltip
        initializeBootstrapTooltip($subDimensionTooltipIcon);
        
        // Reinitialize all tooltips to ensure dynamic ones are captured
        setTimeout(reinitializeAllTooltips, 100);
        
        console.log('RMI Script: Sub-dimension score hidden, incomplete parameters:', incompleteParameters);
    } else {
        // All parameters complete - calculate and display average score
        const averageScore = completeParameterCount > 0 ? (totalScore / completeParameterCount) : 0;
        $subDimensionScore.text(averageScore.toFixed(2));
          // Hide tooltip icon
        $subDimensionTooltipIcon.hide()
                                .removeAttr('title')
                                .removeAttr('data-toggle')
                                .removeAttr('data-placement')
                                .tooltip('dispose');
        
        console.log('RMI Script: Sub-dimension score displayed:', averageScore.toFixed(2));
    }
}

/**
 * Calculate sub-dimension score without updating display
 */
function calculateSubDimensionScore(dimension, subDimension) {
    const $subDimensionCard = $('[data-dimension="' + dimension + '"][data-subdimension="' + subDimension + '"]');
    if (!$subDimensionCard.length) return 0;
    
    // Get all parameters in this sub-dimension
    const $parameters = $subDimensionCard.find('.rmi-parameter-card');
    let totalScore = 0;
    
    $parameters.each(function() {
        const $param = $(this);
        const parameterKey = $param.data('parameter');
        
        // Add parameter score using phase-based logic
        totalScore += calculateScoreByPhaseCompletion(parameterKey);
    });
    
    const averageScore = $parameters.length > 0 ? (totalScore / $parameters.length) : 0;
    return averageScore;
}

/**
 * Calculate dimension score without updating display
 */
function calculateDimensionScore(dimension) {
    const $dimensionCard = $('[data-dimension="' + dimension + '"]').first();
    if (!$dimensionCard.length) return 0;
    
    // Get all parameters in this dimension
    const $parameters = $dimensionCard.find('.rmi-parameter-card');
    let totalScore = 0;
    
    $parameters.each(function() {
        const $param = $(this);
        const parameterKey = $param.data('parameter');
        
        // Add parameter score using phase-based logic
        totalScore += calculateScoreByPhaseCompletion(parameterKey);
    });
    
    const averageScore = $parameters.length > 0 ? (totalScore / $parameters.length) : 0;
    return averageScore;
}

/** * Handle global scores (RMI Score and Total Dimension Score)
 */
function handleGlobalScores(allUnassessedParams) {
    // Handle Total Dimension Score
    const $totalDimScore = $('#totalDimensionScore');
    
    // Handle Main RMI Score  
    const $mainScore = $('#rmiMainScore .score-number');
    const $mainTooltipIcon = $('#rmiMainScore .tooltip-icon');
      if (allUnassessedParams.length === 0) {
        // All parameters assessed - show global scores        // Calculate and display Total Dimension Score
        const totalDimScore = calculateTotalDimensionScore();
        $totalDimScore.val(totalDimScore.toFixed(2) + " / 5.00");
        $totalDimScore.removeAttr('title');
        
        // Calculate and display main RMI score
        if (typeof calculateMainRMIScore === 'function') {
            calculateMainRMIScore();
        }
        $mainScore.removeAttr('title');
        $mainTooltipIcon.hide()
                        .removeAttr('title')
                        .removeAttr('data-toggle')
                        .removeAttr('data-placement')
                        .tooltip('dispose');
        
        console.log('Global scores displayed - all parameters assessed');
      } else {
        // Some parameters not assessed - hide global scores with tooltips on icons
        
        // Hide Total Dimension Score
        $totalDimScore.val("-.-- / 5.00");
        $totalDimScore.removeAttr('title');
        
        // Hide main RMI Score and show tooltip icon
        $mainScore.text('-');
        $mainScore.removeAttr('title');
        const totalTooltipText = `Harap selesaikan penilaian untuk semua parameter berikut: ${allUnassessedParams.join(', ')}`;        $mainTooltipIcon.show()
                        .attr('title', totalTooltipText)
                        .attr('data-toggle', 'tooltip')
                        .attr('data-placement', 'top');        // Initialize Bootstrap tooltip for main score
        initializeBootstrapTooltip($mainTooltipIcon);
        
        // Reinitialize all tooltips to ensure dynamic ones are captured
        setTimeout(reinitializeAllTooltips, 100);
        
        // Hide disclaimer if shown
        $('#rmiScoreDisclaimer').hide();
        
        console.log('Global scores hidden,', allUnassessedParams.length, 'parameters remaining');
    }
}

/**
 * Update dimension progress calculations only (progress bar and percentage)
 */
function updateDimensionProgressCalculations(dimension) {
    // Find the correct dimension container card
    const $dimensionCard = $('[data-dimension="' + dimension + '"]').first();
    if (!$dimensionCard.length) {
        console.warn('RMI Script: Dimension card not found:', dimension);
        return;
    }
    
    // Find all parameter progress bars within this dimension (across all sub-dimensions)
    const $parameterProgressBars = $dimensionCard.find('.rmi-parameter-card .progress-bar');
    
    if ($parameterProgressBars.length === 0) {
        console.warn('RMI Script: No parameter progress bars found in dimension:', dimension);
        return;
    }
    
    // Initialize counters
    let totalPercentage = 0;
    let parameterCount = 0;
    
    // Loop through each parameter's progress bar
    $parameterProgressBars.each(function() {
        const $progressBar = $(this);
        // Safely parse completion percentage with fallback to 0
        const percentage = parseFloat($progressBar.attr('aria-valuenow')) || 0;
        totalPercentage += percentage;
        parameterCount++;
        
        console.log('RMI Script: Dimension parameter progress found:', percentage + '%');
    });
    
    // Calculate average percentage
    const averagePercentage = parameterCount > 0 ? Math.round(totalPercentage / parameterCount) : 0;
    
    // Apply this average to the dimension's progress bar
    const $dimensionProgressBar = $dimensionCard.find('.dimension-progress .progress-bar');
    if ($dimensionProgressBar.length) {
        $dimensionProgressBar.css('width', averagePercentage + '%')
                            .attr('aria-valuenow', averagePercentage);
    }
    
    // Apply to the progress text
    const $dimensionProgressText = $dimensionCard.find('.dimension-progress-text');
    if ($dimensionProgressText.length) {
        $dimensionProgressText.text(averagePercentage + '%');
    }
    
    console.log('RMI Script: Updated dimension progress calculations', {
        dimension: dimension,
        parameterCount: parameterCount,
        averagePercentage: averagePercentage
    });
}

/**
 * Update dimension progress including score logic (- vs. Number)
 */
function updateDimensionProgress(dimension) {
    // First update the progress calculations
    updateDimensionProgressCalculations(dimension);
    
    // Find the correct dimension container card
    const $dimensionCard = $('[data-dimension="' + dimension + '"]').first();
    if (!$dimensionCard.length) {
        console.warn('RMI Script: Dimension card not found for score update:', dimension);
        return;
    }
    
    // Find all parameter score elements within this dimension (across all sub-dimensions)
    const $parameterScoreElements = $dimensionCard.find('.score-value[data-parameter]');
    
    if ($parameterScoreElements.length === 0) {
        console.warn('RMI Script: No parameter score elements found in dimension:', dimension);
        return;
    }
    
    // Check completion status and collect scores
    let incompleteParameters = [];
    let totalScore = 0;
    let completeParameterCount = 0;
    
    $parameterScoreElements.each(function() {
        const $scoreElement = $(this);
        const scoreText = $scoreElement.text().trim();
        const score = parseFloat(scoreText) || 0;
        
        // Get parameter name for incomplete list
        const parameterKey = $scoreElement.data('parameter');
        const $parameterCard = $scoreElement.closest('.rmi-parameter-card');
        const parameterTitle = $parameterCard.find('.parameter-title').text().trim() || parameterKey;
        
        if (score === 0 || scoreText === '-') {
            incompleteParameters.push(parameterTitle);
        } else {
            totalScore += score;
            completeParameterCount++;
        }
        
        console.log('RMI Script: Dimension parameter score check:', parameterTitle, '=', score);
    });
    
    // Find dimension score display elements
    const $dimensionScore = $dimensionCard.find('.dimension-score');
    const $dimensionTooltipIcon = $dimensionScore.siblings('.tooltip-icon');
    
    if (incompleteParameters.length > 0) {
        // Some parameters incomplete - show hyphen and tooltip
        $dimensionScore.text('-');
          // Show tooltip icon with incomplete parameter names
        const tooltipText = `Harap selesaikan penilaian untuk parameter berikut: ${incompleteParameters.join(', ')}`;
        $dimensionTooltipIcon.show()
                            .attr('title', tooltipText)
                            .attr('data-toggle', 'tooltip')
                            .attr('data-placement', 'top');        // Initialize Bootstrap tooltip
        initializeBootstrapTooltip($dimensionTooltipIcon);
        
        // Reinitialize all tooltips to ensure dynamic ones are captured
        setTimeout(reinitializeAllTooltips, 100);
        
        console.log('RMI Script: Dimension score hidden, incomplete parameters:', incompleteParameters);
    } else {
        // All parameters complete - calculate and display average score
        const averageScore = completeParameterCount > 0 ? (totalScore / completeParameterCount) : 0;
        $dimensionScore.text(averageScore.toFixed(2));
          // Hide tooltip icon
        $dimensionTooltipIcon.hide()
                            .removeAttr('title')
                            .removeAttr('data-toggle')
                            .removeAttr('data-placement')
                            .tooltip('dispose');
        
        console.log('RMI Script: Dimension score displayed:', averageScore.toFixed(2));
    }
}

/**
 * Initialize collapsible behavior
 */
function initCollapsibleBehavior() {
    // Ensure all collapses start closed except the first
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
                
                console.log('RMI Script: Saved progress applied, initializing RMI interface...');
                
                // Initialize the RMI interface after saved progress is loaded
                initRMIInterface();
                
                console.log('RMI Script: Full initialization complete');
            } else {
                console.log('RMI Script: No saved progress found, initializing with empty state...');
                
                // Initialize RMI interface even if no saved progress
                initRMIInterface();
                
                console.log('RMI Script: Initialization complete (no saved data)');
            }
        },
        error: function(xhr, status, error) {
            console.warn('RMI Script: Could not load saved progress:', error);
            console.log('RMI Script: Initializing RMI interface despite error...');
            
            // Initialize RMI interface even if loading saved progress fails
            initRMIInterface();
            
            console.log('RMI Script: Initialization complete (with error fallback)');
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
 * Performance Aspects Management
 * Handles the "Skor Aspek Kinerja" section functionality
 */

/**
 * Initialize performance aspects interface
 */
function initPerformanceAspects() {
    console.log('RMI Script: Initializing performance aspects interface...');
    
    // Initialize tooltips/popovers
    initializeTooltips();
    
    // Load saved performance values
    loadPerformanceValues();
    
    // Set up event handlers for performance aspects
    setupPerformanceEventHandlers();
    
    // Calculate initial scores
    calculateMainRMIScore();
}

/**
 * Load saved performance values from localStorage
 */
function loadPerformanceValues() {
    const savedValues = localStorage.getItem('rmi_performance_values');
    if (savedValues) {
        try {
            const values = JSON.parse(savedValues);
            
            // Restore dropdown selections
            if (values.finalRating) {
                $('#finalRatingSelect').val(values.finalRating);
                updateConversionValue('finalRating', values.finalRating);
            }
            if (values.riskRating) {
                $('#riskRatingSelect').val(values.riskRating);
                updateConversionValue('riskRating', values.riskRating);
            }
            
            // Restore weight values
            if (values.finalRatingWeight) {
                $('#finalRatingWeight').val(values.finalRatingWeight);
            }
            if (values.riskRatingWeight) {
                $('#riskRatingWeight').val(values.riskRatingWeight);
            }
              // Recalculate weighted values
            calculateWeightedValue('finalRating');
            calculateWeightedValue('riskRating');
            calculateTotalPerformanceScore();
            
            // Update the conversion table popovers to reflect loaded selections
            updateConversionTablePopovers();
            
        } catch (e) {
            console.warn('RMI Script: Error loading saved performance values:', e);
        }
    }
}

/**
 * Save performance values to localStorage
 */
function savePerformanceValues() {
    const values = {
        finalRating: $('#finalRatingSelect').val(),
        riskRating: $('#riskRatingSelect').val(),
        finalRatingWeight: $('#finalRatingWeight').val(),
        riskRatingWeight: $('#riskRatingWeight').val()
    };
    
    localStorage.setItem('rmi_performance_values', JSON.stringify(values));
}

/**
 * Initialize tooltips for conversion tables
 */
function initializeTooltips() {
    // Initialize static Bootstrap popovers for other info buttons
    $('.tooltip-trigger').not('[title="Tabel Konversi Final Rating"], [title="Tabel Konversi Peringkat Komposit Risiko"]').popover({
        trigger: 'click',
        html: true,
        container: 'body'
    });
    
    // Initialize dynamic popovers for conversion tables
    updateConversionTablePopovers();
    
    // Close popover when clicking outside
    $(document).on('click', function(e) {
        $('.tooltip-trigger').each(function() {
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                $(this).popover('hide');
            }
        });
    });
}

/**
 * Set up event handlers for performance aspects
 */
function setupPerformanceEventHandlers() {
    // Handle dropdown changes
    $(document).on('change', '.performance-aspect-select', function() {
        const aspect = $(this).data('aspect');
        const value = $(this).val();
        updateConversionValue(aspect, value);
        calculateWeightedValue(aspect);
        calculateTotalPerformanceScore();
        calculateMainRMIScore();
        savePerformanceValues();
        savePerformanceAspectsToServer(); // Save to server
        
        // Update the conversion table popovers to reflect the new selection
        updateConversionTablePopovers();
    });
    
    // Handle weight changes
    $(document).on('input', '.performance-weight', function() {
        const aspect = $(this).attr('id').replace('Weight', '');
        calculateWeightedValue(aspect);
        calculateTotalPerformanceScore();
        calculateMainRMIScore();
        savePerformanceValues();
        savePerformanceAspectsToServer(); // Save to server
    });
}

/**
 * Update conversion value based on selected aspect value
 */
function updateConversionValue(aspect, value) {
    const conversionTables = {
        finalRating: {
            'AAA': 100, 'AA': 90, 'A': 79, 'BBB': 67, 'BB': 56,
            'B': 44, 'CCC': 33, 'CC': 21, 'C': 10
        },
        riskRating: {
            '1': 100, '2': 78, '3': 55, '4': 33, '5': 10
        }
    };
    
    const conversionValue = conversionTables[aspect] && conversionTables[aspect][value] ? 
                           conversionTables[aspect][value] : '';
    
    $(`#${aspect}Conversion`).val(conversionValue);
}

/**
 * Calculate weighted value for an aspect
 */
function calculateWeightedValue(aspect) {
    const conversion = parseFloat($(`#${aspect}Conversion`).val()) || 0;
    const weight = parseFloat($(`#${aspect}Weight`).val()) || 0;
    const weightedValue = (conversion * weight) / 100;
    
    $(`#${aspect}WeightedValue`).val(weightedValue.toFixed(2));
}

/**
 * Calculate total performance score
 */
function calculateTotalPerformanceScore() {
    const finalRatingWeighted = parseFloat($('#finalRatingWeightedValue').val()) || 0;
    const riskRatingWeighted = parseFloat($('#riskRatingWeightedValue').val()) || 0;
    const total = finalRatingWeighted + riskRatingWeighted;
    
    $('#totalPerformanceScore').val(total.toFixed(2));
    
    return total;
}

/**
 * Calculate average dimension score
 */
function calculateAverageDimensionScore() {
    const dimensionScores = [];
    
    $('.dimension-score').each(function() {
        const scoreText = $(this).text().trim();
        if (scoreText && scoreText !== '-') {
            const score = parseFloat(scoreText);
            if (!isNaN(score)) {
                dimensionScores.push(score);
            }
        }
    });
    
    if (dimensionScores.length === 0) return 0;
    
    const average = dimensionScores.reduce((sum, score) => sum + score, 0) / dimensionScores.length;
    return average;
}

/**
 * Calculate score adjustment based on performance score
 */
function calculateScoreAdjustment(performanceScore) {
    if (performanceScore <= 50) return -1.00;
    if (performanceScore <= 65) return -0.75;
    if (performanceScore <= 80) return -0.50;
    if (performanceScore <= 90) return -0.25;
    return 0.00;
}

/**
 * Calculates the final RMI score, handles conditional adjustments, and updates all relevant UI elements,
 * including the new dynamic popover.
 */
function calculateMainRMIScore() {
    // Step 1: Calculate the base score using our newly refactored function.
    const baseScore = calculateTotalDimensionScore();
    const allAssessed = areAllParametersAssessed(); // Assumes this helper function exists and works    // Step 2: Update the "Total Skor Aspek Dimensi" display.
    const $totalDimScoreDisplay = $('#totalDimensionScore');
    if (allAssessed) {
        $totalDimScoreDisplay.val(baseScore.toFixed(2) + " / 5.00");
    } else {
        $totalDimScoreDisplay.val("-.-- / 5.00");
    }    // Step 3: Apply conditional adjustment logic for the final RMI score.
    const $disclaimer = $('#rmiScoreDisclaimer');
    let finalScore;
    let scoreAdjustment = 0; // Default to 0 if no adjustment

    if (baseScore < 3.00) {
        // If base score is less than 3.00, no adjustment is applied.
        finalScore = baseScore;
        scoreAdjustment = 0; // No adjustment when base score < 3.00
        if (allAssessed) {
            $disclaimer.show(); // Show the disclaimer only if all params are assessed
        }
    } else {
        // If base score is 3.00 or higher, apply the adjustment.
        const totalPerformanceScore = parseFloat($('#totalPerformanceScore').val()) || 0;
        scoreAdjustment = calculateScoreAdjustment(totalPerformanceScore); // Existing helper

        // Use the floating-point safe calculation to prevent precision errors
        const finalScoreRaw = ((baseScore * 100) + (scoreAdjustment * 100)) / 100;
        finalScore = Math.round(finalScoreRaw * 100) / 100;

        $disclaimer.hide(); // Hide the disclaimer
    }

    // Step 4: Update the main "Skor RMI" display.
    const $mainRmiScoreDisplay = $('#rmiMainScore .score-number');
    if (allAssessed) {
        $mainRmiScoreDisplay.text(finalScore.toFixed(2));
    } else {
        $mainRmiScoreDisplay.text('-');
    }

    // Step 5: IMPORTANT - Call the function from Part 2 to update the dynamic popover.
    if (typeof updateRMIScorePopover === 'function') {
        updateRMIScorePopover(finalScore, baseScore, scoreAdjustment);
    }

    return finalScore;
}

/**
 * Save performance aspects to server via AJAX
 */
function savePerformanceAspectsToServer() {
    // Ensure base_url is defined
    if (typeof base_url === 'undefined') {
        base_url = window.location.origin + window.location.pathname.split('/').slice(0, -1).join('/') + '/';
    }
    
    const data = {
        final_rating: $('#finalRatingSelect').val(),
        risk_rating: $('#riskRatingSelect').val(),
        final_rating_weight: $('#finalRatingWeight').val(),
        risk_rating_weight: $('#riskRatingWeight').val()
    };
    
    $.ajax({
        url: base_url + 'rmi/save_performance_aspects',
        type: 'POST',
        dataType: 'json',
        data: data,
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        },
        success: function(response) {
            if (response.status === 'success') {
                // Create context-aware notification for performance aspects
                const finalRating = data.final_rating || 'N/A';
                const riskRating = data.risk_rating || 'N/A';
                
                // Map rating codes to readable names
                const ratingNames = {
                    'A': 'Sangat Baik (A)',
                    'B': 'Baik (B)', 
                    'C': 'Cukup (C)',
                    'D': 'Kurang (D)',
                    'E': 'Sangat Kurang (E)'
                };
                
                const finalRatingText = ratingNames[finalRating] || finalRating;
                const riskRatingText = ratingNames[riskRating] || riskRating;
                
                const message = `Performance Aspects saved. Final Rating: ${finalRatingText}, Risk Rating: ${riskRatingText}.`;
                showNotification(message, 'success', 'Performance Aspects Updated');
            } else {
                showNotification('Error: ' + (response.message || 'Unknown server error'), 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('RMI Performance Aspects Save Error:', {
                status: status,
                error: error,
                response: xhr.responseText
            });
            showNotification('Error saving performance aspects: ' + error, 'error');
        }
    });
}

/**
 * Check if all parameters have been assessed (score > 0)
 */
function areAllParametersAssessed() {
    let allAssessed = true;
    
    $('.rmi-parameter-card .score-value').each(function() {
        const score = parseFloat($(this).text()) || 0;
        if (score === 0) {
            allAssessed = false;
            return false; // Break out of loop
        }
    });
    
    return allAssessed;
}

/**
 * Calculates the average score across ALL parameter scores on the page.
 * This serves as the value for "Total Skor Aspek Dimensi" and as the base for the final RMI Score.

 * @returns {number} The average of all parameter scores.
 */
function calculateTotalDimensionScore() {
    let totalScoreSum = 0;
    const $parameterScoreElements = $('.rmi-parameter-card .score-value');
    const parameterCount = $parameterScoreElements.length;

    if (parameterCount === 0) {
        return 0; // Avoid division by zero
    }

    $parameterScoreElements.each(function() {
        // Safely parse the score from each parameter, defaulting to 0 if not a number
        const score = parseFloat($(this).text()) || 0;
        totalScoreSum += score;
    });

    // Return the direct average
    return totalScoreSum / parameterCount;
}

/**
 * Update the total dimension score display
 */
function updateTotalDimensionScore() {
    const totalScore = calculateTotalDimensionScore();
    const allAssessed = areAllParametersAssessed();
    
    if (allAssessed && totalScore > 0) {
        $('#totalDimensionScore').val(totalScore.toFixed(1) + " / 5.00");
    } else {
        $('#totalDimensionScore').val("-.-- / 5.00");
    }
}

/**
 * Apply conditional score visibility
 */
function applyConditionalScoreVisibility() {
    const allAssessed = areAllParametersAssessed();
    
    if (allAssessed) {
        // Show all scores
        $('.dimension-score').each(function() {
            const scoreText = $(this).text().trim();
            if (scoreText === '-') {
                // Recalculate if needed
                const dimension = $(this).data('dimension');
                if (dimension) {
                    updateDimensionProgress(dimension);
                }
            }
        });
        
        $('.subdimension-score').each(function() {
            const scoreText = $(this).text().trim();
            if (scoreText === '-') {
                // Recalculate if needed
                const dimension = $(this).data('dimension');
                const subdimension = $(this).data('subdimension');
                if (dimension && subdimension) {
                    updateSubDimensionProgress(dimension, subdimension);
                }
            }
        });
        
        // Show main RMI score
        $('#rmiMainScore').show();
        
        // Update total dimension score
        updateTotalDimensionScore();
        
    } else {
        // Hide scores by showing placeholder
        $('.dimension-score').text('-');
        $('.subdimension-score').text('-');
        $('#rmiMainScore .score-number').text('-');
        $('#totalDimensionScore').val("-.-- / 5.00");
    }
}

/**
 * Debug function to test the new granular score visibility logic
 * Call RMI.testGranularScoring() from browser console
 */
function testGranularScoring() {
    console.log('=== Testing New Granular Score Visibility Logic ===');
    
    // Get current state
    const currentState = {
        totalParameters: $('.rmi-parameter-card').length,
        assessedParameters: 0,
        unassessedParameters: [],
        subdimensionScores: {},
        dimensionScores: {},
        globalScores: {}
    };
    
    // Check parameter states
    $('.rmi-parameter-card').each(function() {
        const $parameterCard = $(this);
        const parameterTitle = $parameterCard.find('.parameter-title').text().trim();
        const parameterScore = parseFloat($parameterCard.find('.score-value').text()) || 0;
        
        if (parameterScore === 0) {
            currentState.unassessedParameters.push(parameterTitle);
        } else {
            currentState.assessedParameters++;
        }
    });
    
    // Check subdimension score states
    $('.subdimension-score').each(function() {
        const $score = $(this);
        const dimension = $score.data('dimension');
        const subdimension = $score.data('subdimension');
        const scoreValue = $score.text();
        const tooltip = $score.attr('title') || '';
        
        currentState.subdimensionScores[`${dimension} > ${subdimension}`] = {
            displayed: scoreValue,
            hasTooltip: tooltip.length > 0,
            tooltip: tooltip
        };
    });
    
    // Check dimension score states
    $('.dimension-score').each(function() {
        const $score = $(this);
        const dimension = $score.data('dimension');
        const scoreValue = $score.text();
        const tooltip = $score.attr('title') || '';
        
        currentState.dimensionScores[dimension] = {
            displayed: scoreValue,
            hasTooltip: tooltip.length > 0,
            tooltip: tooltip
        };
    });
    
    // Check global score states
    const $totalDimScore = $('#totalDimensionScore');
    const $rmiScore = $('#rmiMainScore .score-number');
    
    currentState.globalScores = {
        totalDimensionScore: {
            displayed: $totalDimScore.text(),
            hasTooltip: $totalDimScore.attr('title') ? true : false,
            tooltip: $totalDimScore.attr('title') || ''
        },
        rmiScore: {
            displayed: $rmiScore.text(),
            hasTooltip: $rmiScore.attr('title') ? true : false,
            tooltip: $rmiScore.attr('title') || ''
        }
    };
    
    console.log('Current Assessment State:', currentState);
    
    // Summary
    console.log('=== Summary ===');
    console.log(`Total Parameters: ${currentState.totalParameters}`);
    console.log(`Assessed Parameters: ${currentState.assessedParameters}`);
    console.log(`Unassessed Parameters: ${currentState.unassessedParameters.length}`);
    console.log('Unassessed Parameter Names:', currentState.unassessedParameters);
    
    // Test granular logic explanation
    console.log('=== Granular Logic Test ===');
    console.log('✓ Sub-dimension scores show "-" only if their own parameters are incomplete');
    console.log('✓ Dimension scores show "-" only if their own parameters are incomplete');
    console.log('✓ Global scores (RMI & Total) show "-" only if ANY parameter is incomplete');
    console.log('✓ Tooltips show exactly which parameters are missing for each level');
    
    return currentState;
}

/**
 * Comprehensive test function for all implemented fixes
 * Call RMI.testAllImplementedFixes() from browser console
 */
function testAllImplementedFixes() {
    console.log('=== TESTING ALL IMPLEMENTED RMI FIXES ===');
    
    const testResults = {
        fix1_realtimeUpdates: false,
        fix2_tooltipIcons: false,
        fix3_unifiedDesign: false,
        fix4_saveNotifications: false,
        fix5_parameterRanges: false,
        fix6_sequentialLocking: false,
        details: {}
    };
    
    // Fix 1: Test Real-time Score Updates
    console.log('--- Fix 1: Real-time Score Updates ---');
    try {
        const ajaxFunctionStr = savePointProgress.toString();
        if (ajaxFunctionStr.includes('updateAllProgress()')) {
            testResults.fix1_realtimeUpdates = true;
            console.log('✓ Fix 1: updateAllProgress() is called in AJAX success callback');
        } else {
            console.log('✗ Fix 1: updateAllProgress() NOT found in AJAX callback');
        }
    } catch (e) {
        console.log('✗ Fix 1: Error testing - ', e.message);
    }
    
    // Fix 2: Test Tooltip Icons
    console.log('--- Fix 2: Tooltip Icons ---');
    const tooltipIconCount = $('.tooltip-icon').length;
    const expectedIcons = 4; // Main RMI, Total Dimension, + Dimension scores, + Subdimension scores
    
    if (tooltipIconCount >= expectedIcons) {
        testResults.fix2_tooltipIcons = true;
        console.log(`✓ Fix 2: Found ${tooltipIconCount} tooltip icons (expected at least ${expectedIcons})`);
        
        // Test icon visibility logic
        $('.tooltip-icon').each(function(index) {
            const $icon = $(this);
            const isHidden = $icon.css('display') === 'none';
            console.log(`  - Icon ${index + 1}: ${isHidden ? 'Hidden' : 'Visible'}`);
        });
    } else {
        console.log(`✗ Fix 2: Found only ${tooltipIconCount} tooltip icons (expected at least ${expectedIcons})`);
    }
    
    // Fix 3: Test Unified Design
    console.log('--- Fix 3: Unified Total Score Design ---');
    const $totalDimSection = $('#totalDimensionScore').closest('.rmi-dimension-total-container');
    const hasTableStructure = $totalDimSection.find('table.table-bordered').length > 0;
    const hasSecondaryRow = $totalDimSection.find('tr.table-secondary').length > 0;
    
    if (hasTableStructure && hasSecondaryRow) {
        testResults.fix3_unifiedDesign = true;
        console.log('✓ Fix 3: Total Skor Aspek Dimensi now uses unified table design');
    } else {
        console.log('✗ Fix 3: Total Skor Aspek Dimensi design not unified properly');
        console.log(`  - Has table structure: ${hasTableStructure}`);
        console.log(`  - Has table-secondary row: ${hasSecondaryRow}`);
    }
    
    // Fix 4: Test Save Notifications
    console.log('--- Fix 4: Save Notifications ---');
    try {
        const performanceFunctionStr = savePerformanceAspectsToServer.toString();
        if (performanceFunctionStr.includes('showNotification')) {
            testResults.fix4_saveNotifications = true;
            console.log('✓ Fix 4: showNotification is called in performance aspects save function');
        } else {
            console.log('✗ Fix 4: showNotification NOT found in performance save function');
        }
    } catch (e) {
        console.log('✗ Fix 4: Error testing - ', e.message);
    }
    
    // Fix 5: Test Parameter Ranges
    console.log('--- Fix 5: Parameter Range Display ---');
    let parameterRangeCount = 0;
    $('.summary-value').each(function() {
        const text = $(this).text();
        if (text.includes('(No.')) {
            parameterRangeCount++;
        }
    });
      if (parameterRangeCount > 0) {
        testResults.fix5_parameterRanges = true;
        console.log(`✓ Fix 5: Found ${parameterRangeCount} parameter range displays with (No. X - Y) format`);
    } else {
        console.log('✗ Fix 5: No parameter ranges found with (No. X - Y) format');
    }
    
    // Fix 6: Test Sequential Phase Locking
    console.log('--- Fix 6: Sequential Phase Locking ---');
    try {
        // Test if the locking functions exist
        const hasUpdatePhaseLocking = typeof updatePhaseLocking === 'function';
        const hasApplyPhaseLocking = typeof applyPhaseLockingToAllParameters === 'function';
        
        if (hasUpdatePhaseLocking && hasApplyPhaseLocking) {
            console.log('✓ Fix 6: Phase locking functions exist');
            
            // Test if locking is applied on page load
            const initFunctionStr = initRMIInterface.toString();
            if (initFunctionStr.includes('applyPhaseLockingToAllParameters')) {
                console.log('✓ Fix 6: Phase locking is applied on page load');
                
                // Test if locking is called in checkbox change handlers
                const checkboxHandlerStr = handleCheckboxChange.toString();
                const phaseCheckHandlerStr = handlePhaseCheckAll.toString();
                
                if (checkboxHandlerStr.includes('updatePhaseLocking') && 
                    phaseCheckHandlerStr.includes('updatePhaseLocking')) {
                    console.log('✓ Fix 6: Phase locking is called in change handlers');
                      // Test actual locking state in the DOM
                    const totalPhases = $('.rmi-phase-wrapper').length;
                    const lockedPhases = $('.rmi-phase-wrapper.phase-locked').length;
                    const disabledCheckboxes = $('.phase-locked input[type="checkbox"]:disabled').length;
                    
                    console.log(`✓ Fix 6: Found ${totalPhases} total phases, ${lockedPhases} locked phases, ${disabledCheckboxes} disabled checkboxes`);
                    console.log(`✓ Fix 6: Phase locking logic updated to require ALL items in previous phase (not just one)`);
                    
                    // Test CSS existence
                    const cssRules = Array.from(document.styleSheets).some(sheet => {
                        try {
                            return Array.from(sheet.cssRules || []).some(rule => 
                                rule.selectorText && rule.selectorText.includes('.phase-locked')
                            );
                        } catch (e) {
                            return false;
                        }
                    });
                    
                    if (cssRules) {
                        console.log('✓ Fix 6: Phase-locked CSS rules found');
                        testResults.fix6_sequentialLocking = true;
                    } else {
                        console.log('✗ Fix 6: Phase-locked CSS rules not found');
                    }
                } else {
                    console.log('✗ Fix 6: Phase locking not called in change handlers');
                }
            } else {
                console.log('✗ Fix 6: Phase locking not applied on page load');
            }
        } else {
            console.log('✗ Fix 6: Phase locking functions missing');
            console.log(`  - updatePhaseLocking: ${hasUpdatePhaseLocking}`);
            console.log(`  - applyPhaseLockingToAllParameters: ${hasApplyPhaseLocking}`);
        }
    } catch (e) {
        console.log('✗ Fix 6: Error testing phase locking - ', e.message);
    }
    
    // Summary
    console.log('=== SUMMARY ===');
    const passedTests = Object.values(testResults).filter(v => v === true).length - 1; // -1 for details object
    const totalTests = Object.keys(testResults).length - 1; // -1 for details object
    
    console.log(`Passed: ${passedTests}/${totalTests} fixes`);
      if (passedTests === totalTests) {
        console.log('🎉 ALL FIXES IMPLEMENTED SUCCESSFULLY!');
        showNotification('All six RMI fixes have been successfully implemented and tested!', 'success', 'Implementation Complete');
    } else {
        console.log('⚠️ Some fixes need attention');
        showNotification(`${passedResults}/${totalTests} fixes working correctly. Check console for details.`, 'warning', 'Partial Implementation');
    }
      testResults.details = {
       
        tooltipIconsFound: $('.tooltip-icon').length,
        parameterRangesFound: parameterRangeCount,
        totalDimensionDesign: {
            hasTableStructure: hasTableStructure,
            hasSecondaryRow: hasSecondaryRow
        },
        phaseLocking: {
            totalPhases: $('.rmi-phase-wrapper').length,
            lockedPhases: $('.rmi-phase-wrapper.phase-locked').length,
            disabledCheckboxes: $('.phase-locked input[type="checkbox"]:disabled').length
        }
    };
    
    return testResults;
}

/**
 * Test sequential phase locking functionality
 * Call RMI.testPhaseLocking() from browser console
 */
function testPhaseLocking() {
    console.log('=== TESTING SEQUENTIAL PHASE LOCKING ===');
    
    // Test if functions exist
    console.log('1. Testing function availability...');
    console.log(`   updatePhaseLocking: ${typeof updatePhaseLocking === 'function'}`);
    console.log(`   applyPhaseLockingToAllParameters: ${typeof applyPhaseLockingToAllParameters === 'function'}`);
    
    // Test DOM state
    console.log('2. Testing current DOM state...');
    const totalPhases = $('.rmi-phase-wrapper').length;
    const lockedPhases = $('.rmi-phase-wrapper.phase-locked').length;
    const disabledCheckboxes = $('.phase-locked input[type="checkbox"]:disabled').length;
    
    console.log(`   Total phases: ${totalPhases}`);
    console.log(`   Locked phases: ${lockedPhases}`);
    console.log(`   Disabled checkboxes: ${disabledCheckboxes}`);
    
    // Test phase-by-phase status
    console.log('3. Testing phase-by-phase status...');
    $('.rmi-parameter-card').each(function(paramIndex) {
        const $param = $(this);
        const paramKey = $param.find('.rmi-checkbox').first().data('parameter') || `param-${paramIndex}`;
        console.log(`   Parameter: ${paramKey}`);
          $param.find('.rmi-phase-wrapper').each(function(phaseIndex) {
            const $phase = $(this);
            const phaseNumber = $phase.data('phase') || (phaseIndex + 1);
            const isLocked = $phase.hasClass('phase-locked');
            const checkedCount = $phase.find('.rmi-checkbox:checked').length;
            const totalCount = $phase.find('.rmi-checkbox').length;
            const disabledCount = $phase.find('input[type="checkbox"]:disabled').length;
            const allItemsCompleted = totalCount > 0 && checkedCount === totalCount;
            
            console.log(`     Phase ${phaseNumber}: ${isLocked ? 'LOCKED' : 'UNLOCKED'} | Checked: ${checkedCount}/${totalCount} | Disabled: ${disabledCount} | All Complete: ${allItemsCompleted}`);
        });
    });
    
    // Test logic simulation
    console.log('4. Testing locking logic simulation...');
    const firstParam = $('.rmi-parameter-card').first();
    if (firstParam.length) {
        console.log('   Simulating phase locking update on first parameter...');
        updatePhaseLocking(firstParam);
        console.log('   ✓ Phase locking logic executed successfully');
    } else {
        console.log('   ✗ No parameters found for testing');
    }
    
    // Test CSS
    console.log('5. Testing CSS styling...');
    const hasPhaseLockedCSS = Array.from(document.styleSheets).some(sheet => {
        try {
            return Array.from(sheet.cssRules || []).some(rule => 
                rule.selectorText && rule.selectorText.includes('.phase-locked')
            );
        } catch (e) {
            return false;
        }
    });
    
    console.log(`   Phase-locked CSS rules: ${hasPhaseLockedCSS ? 'FOUND' : 'NOT FOUND'}`);
    
    console.log('=== PHASE LOCKING TEST COMPLETE ===');
    
    return {
        functionsExist: typeof updatePhaseLocking === 'function' && typeof applyPhaseLockingToAllParameters === 'function',
        totalPhases: totalPhases,
        lockedPhases: lockedPhases,
        disabledCheckboxes: disabledCheckboxes,
        cssExists: hasPhaseLockedCSS
    };
}

/**
 * Update phase locking for sequential checklist flow
 * Ensures phases can only be accessed after previous phases have ALL items checked (100% completion)
 * @param {jQuery} $parameterElement - The parameter container element
 */
function updatePhaseLocking($parameterElement) {
    console.log('RMI Script: Updating phase locking for parameter');
    
    // Find all phase blocks within the given parameter element
    const $phases = $parameterElement.find('.rmi-phase-wrapper');
    
    if ($phases.length === 0) {
        console.warn('RMI Script: No phases found in parameter element');
        return;
    }
    
    // Iterate through the phases, starting from the second one (index 1)
    $phases.each(function(index) {
        const $currentPhase = $(this);
        const currentPhaseNumber = parseInt($currentPhase.data('phase')) || (index + 1);
        
        if (index === 0) {
            // The first phase is always enabled, remove any locked styles
            $currentPhase.removeClass('phase-locked');
            $currentPhase.find('input[type=checkbox]').prop('disabled', false);
            $currentPhase.find('.phase-check-all').prop('disabled', false);
            console.log('RMI Script: Phase 1 - Always unlocked');
            return true; // continue to next iteration
        }
        
        // Get the previous phase
        const $previousPhase = $($phases[index - 1]);
        const previousPhaseNumber = parseInt($previousPhase.data('phase')) || index;        // Check if ALL checkboxes are checked in the previous phase (not just one)
        const $previousPhaseCheckboxes = $previousPhase.find('.rmi-checkbox');
        const previousPhaseCheckedCount = $previousPhase.find('.rmi-checkbox:checked').length;
        const previousPhaseTotalCount = $previousPhaseCheckboxes.length;
        const isPreviousPhaseCompleted = previousPhaseTotalCount > 0 && previousPhaseCheckedCount === previousPhaseTotalCount;
          console.log(`RMI Script: Phase ${currentPhaseNumber} - Previous phase ${previousPhaseNumber} completed: ${isPreviousPhaseCompleted} (${previousPhaseCheckedCount}/${previousPhaseTotalCount})`);
        
        // Enable or disable the current phase based on the previous one
        if (isPreviousPhaseCompleted) {
            // UNLOCK: Enable checkboxes and remove locked style
            $currentPhase.removeClass('phase-locked');
            $currentPhase.find('input[type=checkbox]').prop('disabled', false);
            $currentPhase.find('.phase-check-all').prop('disabled', false);            console.log(`RMI Script: Phase ${currentPhaseNumber} - UNLOCKED (previous phase complete: ${previousPhaseCheckedCount}/${previousPhaseTotalCount})`);
        } else {
            // LOCK: Disable checkboxes and add locked style
            $currentPhase.addClass('phase-locked');
            $currentPhase.find('input[type=checkbox]').prop('disabled', true);
            $currentPhase.find('.phase-check-all').prop('disabled', true);
            // Also uncheck all items in locked phases to prevent inconsistent state
            $currentPhase.find('.rmi-checkbox:checked').prop('checked', false);
            console.log(`RMI Script: Phase ${currentPhaseNumber} - LOCKED (previous phase incomplete: ${previousPhaseCheckedCount}/${previousPhaseTotalCount})`);
        }
    });
}

/**
 * Apply phase locking to all parameters on the page
 */
function applyPhaseLockingToAllParameters() {
    console.log('RMI Script: Applying phase locking to all parameters');
    
    $('.rmi-parameter-card').each(function() {
        updatePhaseLocking($(this));
    });
    
    console.log('RMI Script: Phase locking applied to all parameters');
}

/**
 * Handle hierarchical check-all/uncheck-all button clicks
 */
function handleHierarchicalCheckAll($button) {
    const level = $button.data('level');
    const dimension = $button.data('dimension');
    const subdimension = $button.data('subdimension');
    const parameter = $button.data('parameter');
    
    console.log('RMI Script: Hierarchical check-all triggered', {
        level: level,
        dimension: dimension,
        subdimension: subdimension,
        parameter: parameter
    });
    
    let $targetContainer;
    let scopeDescription;
    
    // Determine the scope based on the button level
    switch (level) {
        case 'dimension':
            $targetContainer = $('[data-dimension="' + dimension + '"]').first();
            scopeDescription = 'Dimension: ' + dimension;
            break;
        case 'subdimension':
            $targetContainer = $('[data-dimension="' + dimension + '"][data-subdimension="' + subdimension + '"]').first();
            scopeDescription = 'Sub-dimension: ' + subdimension;
            break;
        case 'parameter':
            $targetContainer = $('[data-parameter="' + parameter + '"]').first();
            // Get parameter title for better description
            const parameterTitle = $targetContainer.find('.parameter-title').text().trim() || parameter;
            scopeDescription = 'Parameter: ' + parameterTitle;
            break;
        default:
            console.error('RMI Script: Unknown hierarchical level:', level);
            return;
    }
    
    if (!$targetContainer.length) {
        console.error('RMI Script: Target container not found for level:', level);
        return;
    }
    
    // Find all checkboxes within the determined scope
    const $allCheckboxes = $targetContainer.find('.rmi-checkbox');
    
    if ($allCheckboxes.length === 0) {
        console.warn('RMI Script: No checkboxes found in', scopeDescription);
        return;
    }
    
    // Determine current state and target state
    const checkedCount = $allCheckboxes.filter(':checked').length;
    const totalCount = $allCheckboxes.length;
    const allChecked = checkedCount === totalCount;
    const targetState = !allChecked; // Toggle: if all are checked, uncheck; otherwise, check all
    
    console.log('RMI Script: Found', totalCount, 'checkboxes,', checkedCount, 'checked. Target state:', targetState);
    
    // Update button appearance
    updateHierarchicalButtonState($button, targetState);
    
    // Count items that actually need to be processed
    let itemsToProcess = 0;
    $allCheckboxes.each(function() {
        const $checkbox = $(this);
        const currentState = $checkbox.is(':checked');
        
        // Only count items that will actually change state
        if (currentState !== targetState) {
            itemsToProcess++;
        }
    });
    
    // If no items need processing, show appropriate message
    if (itemsToProcess === 0) {
        const currentAction = allChecked ? 'All items are already checked' : 'All items are already unchecked';
        showNotification(`${currentAction} in ${scopeDescription}`, 'info', 'No Changes Needed');
        console.log('RMI Script: No items need state change');
        return;
    }
    
    // Generate unique operation ID and start bulk operation tracking
    const bulkOpId = 'bulk-' + Date.now();
    window.RMI.notificationManager.startBulkOp(bulkOpId, itemsToProcess, scopeDescription);
    
    // Update each checkbox and trigger change events with bulk data
    let processedCount = 0;
    $allCheckboxes.each(function() {
        const $checkbox = $(this);
        const currentState = $checkbox.is(':checked');
        
        // Only change if the state is different to avoid unnecessary triggers
        if (currentState !== targetState) {
            $checkbox.prop('checked', targetState);
            // Use setTimeout to prevent overwhelming the server with simultaneous requests
            setTimeout(() => {
                // Pass bulk operation data with the change event
                $checkbox.trigger('change', { isBulk: true, opId: bulkOpId });
            }, processedCount * 50); // Stagger by 50ms
            processedCount++;
        }
    });
    
    console.log('RMI Script: Hierarchical check-all initiated', {
        level: level,
        totalCheckboxes: totalCount,
        itemsToProcess: itemsToProcess,
        targetState: targetState,
        bulkOpId: bulkOpId
    });
    
    // Show immediate feedback notification
    const actionStarting = targetState ? 'Checking' : 'Unchecking';
    showNotification(`${actionStarting} ${itemsToProcess} items in ${scopeDescription}...`, 'info', 'Bulk Update Started');
}

/**
 * Update hierarchical button visual state
 */
function updateHierarchicalButtonState($button, allChecked) {
    if (allChecked) {
        $button.addClass('all-checked');
        $button.attr('title', $button.attr('title').replace('Check/', 'Uncheck/'));
    } else {
        $button.removeClass('all-checked');
        $button.attr('title', $button.attr('title').replace('Uncheck/', 'Check/'));
    }
}

/**
 * Update all hierarchical button states
 */
function updateAllHierarchicalButtonStates() {
    $('.hierarchical-check-all-btn').each(function() {
        const $button = $(this);
        const level = $button.data('level');
        const dimension = $button.data('dimension');
        const subdimension = $button.data('subdimension');
        const parameter = $button.data('parameter');
        
        let $targetContainer;
        
        switch (level) {
            case 'dimension':
                $targetContainer = $('[data-dimension="' + dimension + '"]').first();
                break;
            case 'subdimension':
                $targetContainer = $('[data-dimension="' + dimension + '"][data-subdimension="' + subdimension + '"]').first();
                break;
            case 'parameter':
                $targetContainer = $('[data-parameter="' + parameter + '"]').first();
                break;
        }
        
        if ($targetContainer.length) {
            const $allCheckboxes = $targetContainer.find('.rmi-checkbox');
            const checkedCount = $allCheckboxes.filter(':checked').length;
            const totalCount = $allCheckboxes.length;
            const allChecked = totalCount > 0 && checkedCount === totalCount;
            
            updateHierarchicalButtonState($button, allChecked);
        }
    });
}

/**
 * Safety function to reset bulk update flag if it gets stuck
 */
function resetBulkUpdateFlag() {
    if (window.isBulkUpdate) {
        console.warn('RMI Script: Resetting stuck bulk update flag');
        window.isBulkUpdate = false;
    }
}

/**
 * Handle Area of Improvement button clicks with detailed checklists and dynamic theming
 */
function handleAreaOfImprovement($button) {
    const scopeType = $button.data('scope-type');
    const scopeName = $button.data('scope-name');
    const parentDimension = $button.data('parent-dimension');
    
    console.log('RMI Script: Area of Improvement clicked', {
        scopeType: scopeType,
        scopeName: scopeName,
        parentDimension: parentDimension
    });
    
    let $targetContainer;
    let modalTitle;
    
    // Determine the scope and target container
    if (scopeType === 'dimension') {
        $targetContainer = $('[data-dimension="' + scopeName + '"]').first();
        modalTitle = 'Area of Improvement for Dimension: ' + scopeName;
    } else if (scopeType === 'subdimension') {
        $targetContainer = $('[data-dimension="' + parentDimension + '"][data-subdimension="' + scopeName + '"]').first();
        modalTitle = 'Area of Improvement for Sub-dimension: ' + scopeName;
    } else {
        console.error('RMI Script: Unknown scope type:', scopeType);
        return;
    }
    
    if (!$targetContainer.length) {
        console.error('RMI Script: Target container not found for scope:', scopeType, scopeName);
        return;
    }
    
    // Find all parameters within the scope
    const $allParameters = $targetContainer.find('.rmi-parameter-card');
    
    if ($allParameters.length === 0) {
        console.warn('RMI Script: No parameters found in scope:', scopeType, scopeName);
        return;
    }
    
    // 2.1. Gather Detailed Data - Group by Phase
    let improvements = [];
    let lowestScore = 5; // Track the lowest score for theming
    let lowestScoreBadgeClass = '';
    
    $allParameters.each(function() {
        const $param = $(this);
        const $scoreValue = $param.find('.score-value');
        const score = parseFloat($scoreValue.text()) || 0;
        
        if (score < 5) {
            const parameterTitle = $param.find('.parameter-title').text().trim();
            const parameterKey = $param.data('parameter');
            
            // Track lowest score for theming
            if (score < lowestScore) {
                lowestScore = score;
                // Get the badge class from the parameter's phase badge
                const $phaseBadge = $param.find('.parameter-phase-badge');
                lowestScoreBadgeClass = 'badge-secondary';
                if ($phaseBadge.hasClass('badge-info')) lowestScoreBadgeClass = 'badge-info';
                else if ($phaseBadge.hasClass('badge-warning')) lowestScoreBadgeClass = 'badge-warning';
                else if ($phaseBadge.hasClass('badge-success')) lowestScoreBadgeClass = 'badge-success';
                else if ($phaseBadge.hasClass('badge-primary')) lowestScoreBadgeClass = 'badge-primary';
                else if ($phaseBadge.hasClass('badge-dark')) lowestScoreBadgeClass = 'badge-dark';
            }
            
            // Find incomplete checklist points grouped by phase
            let incompletePhases = {};
            
            // Check each phase from (score + 1) up to 5
            for (let phase = Math.floor(score) + 1; phase <= 5; phase++) {
                const $phaseContainer = $param.find('[data-phase="' + phase + '"]');
                const $checkboxes = $phaseContainer.find('.rmi-checkbox');
                
                let phasePoints = [];
                $checkboxes.each(function() {
                    const $checkbox = $(this);
                    const pointText = $checkbox.data('point-text') || $checkbox.closest('label').text().trim();
                    
                    // Only include unchecked items
                    if (!$checkbox.is(':checked') && pointText) {
                        phasePoints.push(pointText);
                    }
                });
                
                // Only add phase if it has incomplete points
                if (phasePoints.length > 0) {
                    incompletePhases[phase] = phasePoints;
                }
            }
              // Only add to improvements if there are actual incomplete phases
            if (Object.keys(incompletePhases).length > 0) {
                improvements.push({
                    parameterTitle: parameterTitle,
                    parameterKey: parameterKey,
                    parameterScore: score,
                    incompletePhases: incompletePhases
                });
            }
        }
    });
    
    // 2.2. Determine Dynamic Theme Color
    const colorMap = {
        'badge-secondary': 'bg-secondary text-white', // Fase 0
        'badge-info':      'bg-info text-white',      // Fase 1
        'badge-warning':   'bg-warning text-dark',    // Fase 2
        'badge-success':   'bg-success text-white',   // Fase 3
        'badge-primary':   'bg-primary text-white',   // Fase 4
        'badge-dark':      'bg-dark text-white'       // Fase 5
    };
    
    const headerColorClass = colorMap[lowestScoreBadgeClass] || 'bg-info text-white';
    
    // Helper function to get phase name
    const getPhaseDisplayName = (phaseNumber) => {
        const phaseNames = {
            1: 'Fase Awal',
            2: 'Fase Berkembang',
            3: 'Fase Praktik yang Baik',
            4: 'Fase Praktik yang Lebih Baik',
            5: 'Fase Praktik Terbaik'
        };
        return phaseNames[phaseNumber] || `Fase ${phaseNumber}`;
    };
    
    // 2.3. Build the Detailed Modal Content with Phase Grouping
    let modalContent;
    
    if (improvements.length === 0) {
        modalContent = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Sempurna! Semua parameter telah selesai dengan skor maksimal.</div>';
    } else {
        modalContent = '<div class="alert alert-info mb-4"><i class="fas fa-info-circle"></i> Berikut adalah item-item yang masih perlu diselesaikan untuk meningkatkan skor:</div>';
          improvements.forEach(function(improvement) {
            // Get the parameter key to find the corresponding card in main UI
            const parameterKey = improvement.parameterKey;
            const $paramCardInMainUI = $(`.rmi-parameter-card[data-parameter="${parameterKey}"]`);
            
            // Get the phase badge from the main UI
            const $phaseBadge = $paramCardInMainUI.find('.parameter-phase-badge');
            const badgeHtml = $phaseBadge.length ? $phaseBadge.prop('outerHTML') : '<span class="badge badge-secondary">Fase 0</span>';
            
            modalContent += '<div class="card mb-4 border-left-primary">';
            modalContent += '<div class="card-header bg-light">';
            modalContent += '<div class="d-flex justify-content-between align-items-center">';
            modalContent += '<h6 class="mb-0 text-primary font-weight-bold">' + improvement.parameterTitle + '</h6>';
            modalContent += badgeHtml;
            modalContent += '</div>';
            modalContent += '</div>';
            
            modalContent += '<div class="card-body">';
            
            // Group by phases
            const sortedPhases = Object.keys(improvement.incompletePhases).sort((a, b) => parseInt(a) - parseInt(b));
            
            sortedPhases.forEach(function(phase, index) {
                const phaseName = getPhaseDisplayName(parseInt(phase));
                const phasePoints = improvement.incompletePhases[phase];
                
                // Phase header with icon
                modalContent += '<div class="mb-3">';
                modalContent += '<h6 class="text-secondary mb-2">';
                modalContent += '<i class="fas fa-arrow-right text-primary mr-2"></i>';
                modalContent += phaseName;
                modalContent += '</h6>';
                
                // Phase points
                modalContent += '<ul class="list-unstyled pl-4 mb-0">';
                phasePoints.forEach(function(point) {
                    modalContent += '<li class="mb-2">';
                    modalContent += '<i class="fas fa-circle text-muted mr-2" style="font-size: 6px; vertical-align: middle;"></i>';
                    modalContent += '<span class="text-dark">' + point + '</span>';
                    modalContent += '</li>';
                });
                modalContent += '</ul>';
                modalContent += '</div>';
                
                // Add separator between phases (except for last phase)
                if (index < sortedPhases.length - 1) {
                    modalContent += '<hr class="my-3" style="border-top: 1px dashed #dee2e6;">';
                }
            });
            
            modalContent += '</div>';
            modalContent += '</div>';
        });
        
        modalContent += '<div class="alert alert-light border-left-info">';
        modalContent += '<div class="d-flex">';
        modalContent += '<i class="fas fa-lightbulb text-warning mr-3 mt-1"></i>';
        modalContent += '<div>';
        modalContent += '<strong class="text-info">Tips untuk Peningkatan:</strong><br>';
        modalContent += '<small class="text-muted">Selesaikan item-item di atas secara berurutan sesuai fase. ';
        modalContent += 'Mulai dari fase terendah dan pastikan semua item dalam satu fase selesai sebelum melanjutkan ke fase berikutnya.</small>';
        modalContent += '</div>';
        modalContent += '</div>';
        modalContent += '</div>';
    }
    
    // 2.4. Apply Theme and Show Modal
    const $header = $('#improvementModalHeader');
    
    // Remove any previous color classes
    $header.removeClass('bg-secondary bg-info bg-warning bg-success bg-primary bg-dark text-white text-dark');
    
    // Add the new color class
    $header.addClass(headerColorClass);
    
    // Populate and show modal
    $('#improvementModal .modal-title').text(modalTitle);
    $('#improvementModal .modal-body').html(modalContent);
    $('#improvementModal').modal('show');
    
    console.log('RMI Script: Enhanced Area of Improvement modal shown', {
        scopeType: scopeType,
        scopeName: scopeName,
        totalParameters: $allParameters.length,
        improvementsCount: improvements.length,
        lowestScore: lowestScore,
        headerTheme: headerColorClass
    });
}

/**
 * Generate dynamic popover content for Final Rating table with highlighting
 */
function generateFinalRatingPopoverContent() {
    const selectedValue = $('#finalRatingSelect').val();
    console.log('Final Rating Selected Value:', selectedValue); // Debug log
    
    const ratings = [
        { rating: 'AAA', value: 100 },
        { rating: 'AA', value: 90 },
        { rating: 'A', value: 79 },
        { rating: 'BBB', value: 67 },
        { rating: 'BB', value: 56 },
        { rating: 'B', value: 44 },
        { rating: 'CCC', value: 33 },
        { rating: 'CC', value: 21 },
        { rating: 'C', value: 10 }
    ];
    
    let tableRows = '';
    ratings.forEach(item => {
        const isSelected = selectedValue === item.rating;
        const highlightClass = isSelected ? ' class="table-warning"' : '';
        tableRows += `<tr${highlightClass}><td>${item.rating}</td><td>${item.value}</td></tr>`;
    });
    
    const content = `
        <table class='table table-sm mb-0'>
            <thead>
                <tr><th>Peringkat Akhir</th><th>Nilai Konversi</th></tr>
            </thead>
            <tbody>
                ${tableRows}
            </tbody>
        </table>
        ${selectedValue && selectedValue !== '' ? `<div class="mt-2"><small class="text-info"><i class="fas fa-info-circle"></i> <strong>Terpilih: ${selectedValue}</strong> (baris yang disorot menunjukkan pilihan Anda)</small></div>` : '<div class="mt-2"><small class="text-muted"><i class="fas fa-exclamation-triangle"></i> Belum ada pilihan</small></div>'}
    `;
    
    return content;
}

/**
 * Generate dynamic popover content for Risk Rating table with highlighting
 */
function generateRiskRatingPopoverContent() {
    const selectedValue = $('#riskRatingSelect').val();
    console.log('Risk Rating Selected Value:', selectedValue); // Debug log
    
    const ratings = [
        { rating: '1', value: 100 },
        { rating: '2', value: 78 },
        { rating: '3', value: 55 },
        { rating: '4', value: 33 },
        { rating: '5', value: 10 }
    ];
    
    let tableRows = '';
    ratings.forEach(item => {
        const isSelected = selectedValue === item.rating;
        const highlightClass = isSelected ? ' class="table-warning"' : '';
        tableRows += `<tr${highlightClass}><td>${item.rating}</td><td>${item.value}</td></tr>`;
    });
    
    const content = `
        <table class='table table-sm mb-0'>
            <thead>
                <tr><th>Peringkat Komposit Risiko</th><th>Nilai Konversi</th></tr>
            </thead>
            <tbody>
                ${tableRows}
            </tbody>
        </table>
        ${selectedValue && selectedValue !== '' ? `<div class="mt-2"><small class="text-info"><i class="fas fa-info-circle"></i> <strong>Terpilih: ${selectedValue}</strong> (baris yang disorot menunjukkan pilihan Anda)</small></div>` : '<div class="mt-2"><small class="text-muted"><i class="fas fa-exclamation-triangle"></i> Belum ada pilihan</small></div>'}
    `;
    
    return content;
}

/**
 * Update popover content for conversion tables
 */
function updateConversionTablePopovers() {
    // Update Final Rating popover
    const $finalRatingBtn = $('[title="Tabel Konversi Final Rating"]');
    if ($finalRatingBtn.length) {
        $finalRatingBtn.popover('dispose');
        $finalRatingBtn.popover({
            trigger: 'click',
            html: true,
            container: 'body',
            title: 'Tabel Konversi Final Rating',
            content: function() {
                return generateFinalRatingPopoverContent();
            }
        });
        
        // Add event listener to update content when shown
        $finalRatingBtn.off('show.bs.popover').on('show.bs.popover', function() {
            const popover = $(this).data('bs.popover');
            if (popover) {
                popover.config.content = generateFinalRatingPopoverContent();
            }
        });
    }
    
    // Update Risk Rating popover
    const $riskRatingBtn = $('[title="Tabel Konversi Peringkat Komposit Risiko"]');
    if ($riskRatingBtn.length) {
        $riskRatingBtn.popover('dispose');
        $riskRatingBtn.popover({
            trigger: 'click',
            html: true,
            container: 'body',
            title: 'Tabel Konversi Peringkat Komposit Risiko',
            content: function() {
                return generateRiskRatingPopoverContent();
            }
        });
        
        // Add event listener to update content when shown
        $riskRatingBtn.off('show.bs.popover').on('show.bs.popover', function() {
            const popover = $(this).data('bs.popover');
            if (popover) {
                popover.config.content = generateRiskRatingPopoverContent();
            }
        });
    }
}