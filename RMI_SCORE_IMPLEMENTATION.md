# RMI Score Calculation Feature Implementation

## Overview
This document describes the implementation of the new RMI (Risk Management Index) scoring features added to the assessment page.

## New Features Added

### 1. Main RMI Score Display
- **Location**: Top of the Penilaian tab, before "Aspek Dimensi RMI"
- **Purpose**: Displays the final calculated RMI score
- **Calculation**: Average Dimension Score - Score Adjustment
- **Special Condition**: If Average Dimension Score < 3.00, no adjustment is applied and a disclaimer is shown

### 2. Skor Aspek Kinerja Section
- **Location**: Below the existing "Aspek Dimensi RMI" section
- **Purpose**: Calculate performance aspect scores that affect the main RMI score
- **Components**:
  - Tingkat Kesehatan Peringkat Akhir (Final Rating) dropdown
  - Peringkat Komposit Risiko dropdown
  - Weight inputs (default 50% each)
  - Automatic conversion value calculation
  - Weighted value calculation
  - Total performance score

## File Modifications

### 1. View File: `application/views/rmi/rmi_view.php`
- Added main RMI score display container
- Added performance aspects table with tooltips
- Integrated Bootstrap popovers for conversion tables

### 2. JavaScript: `assets/js/rmi_script.js`
- Added performance aspects initialization
- Added conversion value calculation functions
- Added main RMI score calculation logic
- Added localStorage persistence for performance values
- Updated existing progress functions to recalculate main score

### 3. CSS: `assets/css/rmi_style.css`
- Added styles for main RMI score display
- Added styles for performance aspects table
- Added tooltip and popover styling
- Added responsive design considerations

## Calculation Logic

### Main RMI Score Formula
```
IF Average Dimension Score < 3.00:
    RMI Score = Average Dimension Score (no adjustment)
ELSE:
    RMI Score = Average Dimension Score + Score Adjustment
```

### Score Adjustment Table
| Total Performance Score (x) | Score Adjustment |
|------------------------------|------------------|
| x ≤ 50                      | -1.00            |
| 50 < x ≤ 65                 | -0.75            |
| 65 < x ≤ 80                 | -0.50            |
| 80 < x ≤ 90                 | -0.25            |
| x > 90                      | 0.00             |

### Conversion Tables

#### Final Rating Conversion
| Rating | Value |
|--------|-------|
| AAA    | 100   |
| AA     | 90    |
| A      | 79    |
| BBB    | 67    |
| BB     | 56    |
| B      | 44    |
| CCC    | 33    |
| CC     | 21    |
| C      | 10    |

#### Risk Rating Conversion
| Rating | Value |
|--------|-------|
| 1      | 100   |
| 2      | 78    |
| 3      | 55    |
| 4      | 33    |
| 5      | 10    |

## Technical Features

### Real-time Updates
- Main RMI score updates automatically when:
  - Dimension scores change (via checkbox completion)
  - Performance aspect values change
  - Weight values are modified

### Data Persistence
- Performance aspect selections are saved to localStorage
- Values persist across page refreshes
- Automatic restoration on page load

### User Interface
- Bootstrap tooltips show conversion tables
- Responsive design for mobile devices
- Visual feedback for score changes
- Disclaimer display for special conditions

## Dependencies
- jQuery (already loaded)
- Bootstrap 4 (already loaded)
- FontAwesome 6 (already loaded)
- CoreUI framework (already loaded)

## Testing
- All functions are exported to `window.RMI` for console testing
- No syntax errors detected
- Responsive design tested for mobile/tablet views
- Cross-browser compatibility maintained

## Maintenance Notes
- Performance values are stored in localStorage with key: `rmi_performance_values`
- Main scoring logic is in `calculateMainRMIScore()` function
- To modify conversion tables, update `updateConversionValue()` function
- To change score adjustment rules, update `calculateScoreAdjustment()` function
