/* RMI Assessment JavaScript - Interactive Functionality */
$(document).ready(function() {
    console.log('RMI Assessment Interactive Module loaded');
    
    // Initialize RMI functionality
    initializeRMI();
    
    // Load saved progress
    loadSavedProgress();
    
    // Setup event listeners
    setupEventListeners();
});

function initializeRMI() {
    // Initialize all progress bars and scores
    initializeAllCards();
    
    // Set up initial phase state - only first phase shown
    $('.rmi-phase-wrapper[data-phase="1"] .collapse').addClass('show');
    $('.rmi-phase-wrapper[data-phase!="1"] .collapse').removeClass('show');
    
    // Set button states correctly
    $('.rmi-phase-wrapper[data-phase="1"] .rmi-phase-header').attr('aria-expanded', 'true').removeClass('collapsed');
    $('.rmi-phase-wrapper[data-phase!="1"] .rmi-phase-header').attr('aria-expanded', 'false').addClass('collapsed');
    
    console.log('RMI initialized successfully');
}

function setupEventListeners() {
    // Updated checkbox change event with new structure
    $(document).on('change', '.rmi-parameter-card .rmi-checkbox', function() {
        // 'this' refers to the checkbox that was clicked
        updateProgressAndScore(this);

        // Keep existing AJAX call for auto-saving
        const checkbox = $(this);
        handleCheckboxChange(checkbox);
    });
    
    // Phase header click event (let Bootstrap handle collapse, but add custom logic)
    $(document).on('click', '.rmi-phase-header', function() {
        const header = $(this);
        console.log('Phase header clicked:', header.closest('.rmi-phase-wrapper').data('phase'));
    });
    
    // Bootstrap collapse events for icon rotation
    setupCollapseEventListeners();
}

function setupCollapseEventListeners() {
    // Handle collapse show event (when opening)
    $(document).on('show.bs.collapse', '.collapse', function() {
        const collapseEl = this;
        const header = $(`[data-target="#${collapseEl.id}"]`);
        if (header.length) {
            const icon = header.find('.toggle-icon');
            if (icon.length) {
                icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
            }
            header.attr('aria-expanded', 'true').removeClass('collapsed');
        }
    });
    
    // Handle collapse hide event (when closing)
    $(document).on('hide.bs.collapse', '.collapse', function() {
        const collapseEl = this;
        const header = $(`[data-target="#${collapseEl.id}"]`);
        if (header.length) {
            const icon = header.find('.toggle-icon');
            if (icon.length) {
                icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
            }
            header.attr('aria-expanded', 'false').addClass('collapsed');
        }
    });
    
    // Handle collapse shown event (after opening animation completes)
    $(document).on('shown.bs.collapse', '.collapse', function() {
        const collapseEl = this;
        console.log('Phase expanded:', collapseEl.id);
    });
    
    // Handle collapse hidden event (after closing animation completes) 
    $(document).on('hidden.bs.collapse', '.collapse', function() {
        const collapseEl = this;
        console.log('Phase collapsed:', collapseEl.id);
    });
}

function handleCheckboxChange(checkbox) {
    const parameterKey = checkbox.data('parameter');
    const phase = checkbox.data('phase');
    const pointId = checkbox.data('point');
    const pointText = checkbox.data('point-text');
    const isCompleted = checkbox.is(':checked');
    
    // Add visual feedback
    checkbox.closest('.rmi-checklist-item').addClass('saving-indicator');
    
    // Save progress via AJAX
    savePointProgress(parameterKey, phase, pointId, pointText, isCompleted, function(success) {
        // Remove loading state
        checkbox.closest('.rmi-checklist-item').removeClass('saving-indicator');
        
        if (success) {
            // Add success feedback
            checkbox.closest('.rmi-checklist-item').addClass('save-success');
            setTimeout(() => {
                checkbox.closest('.rmi-checklist-item').removeClass('save-success');
            }, 500);
            
            // Update progress for this parameter
            updateParameterProgress(parameterKey);
            
            // Check phase completion
            checkPhaseCompletion(parameterKey, phase);
            
            // Calculate and update parameter score
            const parameterCard = checkbox.closest('.rmi-parameter-card');
            calculateAndUpdateScore(parameterCard);
            
            // Update phase progression
            updatePhaseProgression(parameterKey);
        } else {
            // Revert checkbox on error
            checkbox.prop('checked', !isCompleted);
            alert('Failed to save progress. Please try again.');
        }
    });
}

function savePointProgress(parameterKey, phase, pointId, pointText, isCompleted, callback) {
    $.ajax({
        url: 'rmi/save_point_progress',
        type: 'POST',
        dataType: 'json',
        data: {
            parameter_key: parameterKey,
            phase_level: phase,
            point_identifier: pointId,
            point_text: pointText,
            is_completed: isCompleted ? 1 : 0
        },
        success: function(response) {
            if (response.status === 'success') {
                console.log('Progress saved:', response);
                callback(true);
            } else {
                console.error('Save failed:', response.message);
                callback(false);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', error);
            callback(false);
        }
    });
}

/**
 * This single, robust function will now handle both the progress bar
 * and the parameter score calculation.
 * It uses .closest() to correctly find the parent card from any checkbox.
 */
function updateProgressAndScore(checkboxElement) {
    console.log('updateProgressAndScore called');
    
    // 1. Find the main card container for the parameter that was clicked.
    const parameterCard = $(checkboxElement).closest('.rmi-parameter-card');
    if (parameterCard.length === 0) {
        console.warn('Parameter card not found');
        return; // Exit if card not found
    }

    // 2. Get necessary elements and data from within THIS card.
    const progressBar = parameterCard.find('.progress-bar');
    const progressText = parameterCard.find('.progress-text');
    const scoreValueSpan = parameterCard.find('.score-value');

    // Find the accordion element to get total points
    const totalPoints = parseInt(parameterCard.data('total-points'), 10) || 0;
    const allCheckboxesInCard = parameterCard.find('.rmi-checkbox');
    const checkedCheckboxes = allCheckboxesInCard.filter(':checked');

    const checkedCount = checkedCheckboxes.length;

    console.log(`Parameter card found: ${checkedCount}/${totalPoints} checked`);

    // 3. Calculate and Update Progress Bar
    const percentage = totalPoints > 0 ? (checkedCount / totalPoints) * 100 : 0;
    
    if (progressBar.length > 0) {
        progressBar.css('width', percentage + '%').attr('aria-valuenow', percentage);
    }
    
    if (progressText.length > 0) {
        progressText.text(checkedCount + '/' + totalPoints + ' (' + percentage.toFixed(0) + '%)');
    }

    // 4. Calculate and Update Parameter Score
    let currentScore = 0;
    
    for (let i = 1; i <= 5; i++) {
        const phaseWrapper = parameterCard.find('.rmi-phase-wrapper[data-phase="' + i + '"]');
        const checkboxesInPhase = phaseWrapper.find('input[type="checkbox"]');

        // If the phase has no checkboxes, we can't complete it.
        if (checkboxesInPhase.length === 0) {
             break;
        }

        const areAllChecked = checkboxesInPhase.length === checkboxesInPhase.filter(':checked').length;

        if (areAllChecked) {
            currentScore = i;
        } else {
            // Stop at the first incomplete phase
            break; 
        }
    }
    
    if (scoreValueSpan.length > 0) {
        scoreValueSpan.text(currentScore);
    }
    
    console.log(`Parameter score updated to: ${currentScore}`);
    
    // Update phase progress indicators
    updatePhaseProgress(parameterCard);
}

function updatePhaseProgress(parameterCard) {
    // Update each phase's progress indicator
    for (let i = 1; i <= 5; i++) {
        const phaseWrapper = parameterCard.find('.rmi-phase-wrapper[data-phase="' + i + '"]');
        const checkboxesInPhase = phaseWrapper.find('input[type="checkbox"]');
        const checkedInPhase = checkboxesInPhase.filter(':checked');
        const phaseProgressText = phaseWrapper.find('.phase-progress .completed-count');
        const phaseCheckIcon = phaseWrapper.find('.phase-check-icon');
        
        // Update phase progress text
        phaseProgressText.text(checkedInPhase.length);
        
        // Show/hide check icon based on completion
        if (checkboxesInPhase.length > 0 && checkedInPhase.length === checkboxesInPhase.length) {
            phaseCheckIcon.show();
        } else {
            phaseCheckIcon.hide();
        }
    }
}

function updateParameterProgress(parameterKey) {
    const parameterCard = $(`.rmi-parameter-card[data-parameter="${parameterKey}"]`);
    if (parameterCard.length === 0) return;
    
    const firstCheckbox = parameterCard.find('.rmi-checkbox').first();
    if (firstCheckbox.length) {
        updateProgressAndScore(firstCheckbox[0]);
    }
}

function getProgressBarColor(percentage) {
    if (percentage >= 90) return 'bg-success';
    if (percentage >= 70) return 'bg-info';
    if (percentage >= 50) return 'bg-warning';
    return 'bg-danger';
}

function checkPhaseCompletion(parameterKey, phase) {
    const phaseWrapper = $(`.rmi-phases-container[data-parameter="${parameterKey}"] .rmi-phase-wrapper[data-phase="${phase}"]`);
    const phaseCheckboxes = phaseWrapper.find('.rmi-checkbox');
    const checkedBoxes = phaseWrapper.find('.rmi-checkbox:checked');
    const phaseHeader = phaseWrapper.find('.rmi-phase-header');
    const checkIcon = phaseHeader.find('.phase-check-icon');
    const progressSpan = phaseHeader.find('.phase-progress .completed-count');
    
    // Update phase progress counter
    progressSpan.text(checkedBoxes.length);
    
    // Check if phase is completed
    const isCompleted = phaseCheckboxes.length > 0 && phaseCheckboxes.length === checkedBoxes.length;
    
    if (isCompleted) {
        phaseHeader.addClass('completed');
        checkIcon.show();
          // Auto-collapse completed phase after a delay
        setTimeout(() => {
            const collapseTarget = phaseHeader.data('bs-target');
            if (collapseTarget && $(collapseTarget).hasClass('show')) {
                // Use Bootstrap's collapse method
                if (typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
                    const bsCollapse = new bootstrap.Collapse($(collapseTarget)[0], {
                        toggle: false
                    });
                    bsCollapse.hide();
                } else {
                    // Fallback for older Bootstrap versions
                    $(collapseTarget).collapse('hide');
                }
            }
        }, 1500);
    } else {
        phaseHeader.removeClass('completed');
        checkIcon.hide();
    }
    
    return isCompleted;
}

function updatePhaseProgression(parameterKey) {
    const phasesContainer = $(`.rmi-phases-container[data-parameter="${parameterKey}"]`);
    const phases = phasesContainer.find('.rmi-phase-wrapper').sort((a, b) => {
        return parseInt($(a).data('phase')) - parseInt($(b).data('phase'));
    });
    
    let showNext = false;
    let highestCompletedPhase = 0;
    
    phases.each(function(index) {
        const phase = $(this);
        const phaseNumber = parseInt(phase.data('phase'));
        const phaseHeader = phase.find('.rmi-phase-header');
        const isCompleted = phaseHeader.hasClass('completed');
        
        if (isCompleted) {
            highestCompletedPhase = phaseNumber;
        }
        
        // Show current phase and all previous phases, plus one next phase
        if (phaseNumber <= highestCompletedPhase + 1) {
            phase.removeClass('hidden-phase').addClass('show-phase');
        } else {
            phase.removeClass('show-phase').addClass('hidden-phase');
        }
    });
      // If a phase is completed, auto-expand the next phase
    if (highestCompletedPhase > 0) {
        const nextPhase = phasesContainer.find(`.rmi-phase-wrapper[data-phase="${highestCompletedPhase + 1}"]`);
        if (nextPhase.length > 0 && !nextPhase.hasClass('hidden-phase')) {
            setTimeout(() => {
                const nextCollapseTarget = nextPhase.find('.rmi-phase-header').data('bs-target');
                if (nextCollapseTarget && !$(nextCollapseTarget).hasClass('show')) {
                    // Use Bootstrap's collapse method
                    if (typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
                        const bsCollapse = new bootstrap.Collapse($(nextCollapseTarget)[0], {
                            toggle: false
                        });
                        bsCollapse.show();
                    } else {
                        // Fallback for older Bootstrap versions
                        $(nextCollapseTarget).collapse('show');
                        nextPhase.find('.rmi-phase-header').attr('aria-expanded', 'true');
                    }
                }
            }, 2000);
        }
    }
}

function updateAllProgressBars() {
    $('[data-parameter]').each(function() {
        const parameterKey = $(this).data('parameter');
        if (parameterKey) {
            updateParameterProgress(parameterKey);
        }
    });
}

function togglePhaseCollapse(header) {
    // This function is now handled by Bootstrap collapse events
    // Keep for backward compatibility but most logic is in setupCollapseEventListeners
    const isExpanded = header.attr('aria-expanded') === 'true';
    console.log(`Phase ${header.closest('.rmi-phase-wrapper').data('phase')} ${isExpanded ? 'will collapse' : 'will expand'}`);
}

// Parameter Score Calculation
function calculateAndUpdateScore(parameterCard) {
    const parameterKey = parameterCard.data('parameter');
    let currentScore = 0;
    
    // Loop through phases 1 to 5
    for (let phase = 1; phase <= 5; phase++) {
        const phaseWrapper = parameterCard.find(`.rmi-phase-wrapper[data-phase="${phase}"]`);
        
        if (phaseWrapper.length === 0) {
            continue; // Skip if phase doesn't exist
        }
        
        // Check if phase is fully completed
        const totalCheckboxes = phaseWrapper.find('.rmi-checkbox').length;
        const checkedCheckboxes = phaseWrapper.find('.rmi-checkbox:checked').length;
        
        if (totalCheckboxes > 0 && checkedCheckboxes === totalCheckboxes) {
            // Phase is fully completed
            currentScore = phase;
        } else {
            // Phase is not fully completed, stop here
            break;
        }
    }
    
    // Update the score display
    const scoreElement = parameterCard.find('.score-value[data-parameter="' + parameterKey + '"]');
    scoreElement.text(currentScore);
    
    // Update parameter badge
    updateParameterBadge(parameterCard, currentScore);
    
    console.log(`Parameter ${parameterKey} score updated to: ${currentScore}`);
}

function updateParameterBadge(parameterCard, score) {
    const badge = parameterCard.find('.parameter-phase-badge');
    
    // Remove existing phase classes
    badge.removeClass('badge-secondary badge-phase-0 badge-phase-1 badge-phase-2 badge-phase-3 badge-phase-4 badge-phase-5');
    
    // Set new badge text and class
    const phaseNames = {
        0: 'Fase 0',
        1: 'Fase 1',
        2: 'Fase 2', 
        3: 'Fase 3',
        4: 'Fase 4',
        5: 'Fase 5'
    };
    
    badge.text(phaseNames[score] || 'Fase 0');
    badge.addClass(`badge-phase-${score}`);
}

// Initialize scores for all parameters
function initializeAllScores() {
    $('.rmi-parameter-card').each(function() {
        calculateAndUpdateScore($(this));
    });
}

// Initialize all cards with progress and scores
function initializeAllCards() {
    $('.rmi-parameter-card').each(function() {
        const firstCheckbox = $(this).find('.rmi-checkbox').first();
        if (firstCheckbox.length) {
            updateProgressAndScore(firstCheckbox[0]);
        }
    });
}

function loadSavedProgress() {
    $.ajax({
        url: 'rmi/get_saved_progress',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                const progress = response.progress;
                
                // Apply saved checkboxes
                Object.keys(progress).forEach(function(key) {
                    const parts = key.split('_');
                    if (parts.length >= 3) {
                        const parameterKey = parts[0];
                        const phase = parts[1];
                        const pointId = parts[2];
                        const checkboxId = `${parameterKey}_${phase}_${pointId}`;
                        $(`#${checkboxId}`).prop('checked', true);
                    }
                });
                  // Update all progress displays
                updateAllProgressBars();
                
                // Recalculate all parameter scores
                initializeAllScores();
                
                // Update phase completions
                $('.rmi-phases-container').each(function() {
                    const parameterKey = $(this).data('parameter');
                    for (let phase = 1; phase <= 5; phase++) {
                        checkPhaseCompletion(parameterKey, phase);
                    }
                    updatePhaseProgression(parameterKey);
                });
                
                console.log('Saved progress loaded successfully');
            }
        },
        error: function(xhr, status, error) {
            console.error('Failed to load saved progress:', error);
        }
    });
}
