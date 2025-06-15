<!-- Load RMI specific styles -->
<link href="<?= base_url('assets/css/rmi_style.css'); ?>" rel="stylesheet">

<main class="main">
				<!-- Breadcrumb-->
				<ol class="breadcrumb">
					<li class="breadcrumb-item">Home</li>
					<li class="breadcrumb-item">
						<a href="#">Admin</a>
					</li>
					<li class="breadcrumb-item active">RMI Assessment</li>
					<!-- Breadcrumb Menu-->
					<li class="breadcrumb-menu d-md-down-none">
						<div class="btn-group" role="group" aria-label="Button group">
							<a class="btn" href="#">
								<i class="icon-speech"></i>
							</a>
							<a class="btn" href="./">
								<i class="icon-graph"></i>  Dashboard</a>
							<a class="btn" href="#">
								<i class="icon-settings"></i>  Settings</a>
						</div>
					</li>
				</ol>
				<div class="body flex-grow-1 px-3">
					<div class="container-lg">
						<div class="card mb-4">
							<div class="card-body rmi-container">
								<!-- Tabbed Interface -->
								<ul class="nav nav-tabs rmi-tabs" id="rmiTabs" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" id="penilaian-tab" data-toggle="tab" href="#penilaian" role="tab" aria-controls="penilaian" aria-selected="true">
											<i class="icon-list"></i> Penilaian
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="laporan-tab" data-toggle="tab" href="#laporan" role="tab" aria-controls="laporan" aria-selected="false">
											<i class="icon-chart"></i> AOI
										</a>
									</li>
								</ul>								<!-- Tab Content -->
								<div class="tab-content rmi-tab-content" id="rmiTabContent">									<!-- Penilaian Tab -->
									<div class="tab-pane show active" id="penilaian" role="tabpanel" aria-labelledby="penilaian-tab">
										
										<!-- Main RMI Score Display -->
										<div class="rmi-main-score-container mb-4">
											<div class="card rmi-main-score-card">
												<div class="card-body text-center">
													<h3 class="rmi-main-score-title">Skor RMI</h3>													<div class="rmi-main-score-value" id="rmiMainScore">
														<span class="score-number">0.00</span>
														<i class="fas fa-question-circle tooltip-icon" style="display: none; cursor: help; margin-left: 8px;"></i>
													</div>
													<div class="rmi-score-disclaimer" id="rmiScoreDisclaimer" style="display: none;">
														<small class="text-muted">Tidak dilakukan penyesuaian skor karena skor Aspek Dimensi Penilaian RMI yang diperoleh < 3,00.</small>
													</div>
												</div>
											</div>
										</div>
										
										<h2 class="mb-4">Aspek Dimensi RMI</h2>
										
										<!-- Hierarchical Structure Container -->
										<div class="rmi-hierarchical-container">
											<?php if (!empty($rmi_data) && !empty($dimension_summaries)): ?>
												
												<!-- Level 1: Dimensions Overview -->
												<div class="rmi-dimensions-level">
													<?php foreach ($dimension_summaries as $dimensi => $dimension_data): ?>
														<div class="rmi-dimension-card" data-dimension="<?= htmlspecialchars($dimensi) ?>">
															<div class="dimension-header" role="button" data-toggle="collapse" 
																 data-target="#dimension-<?= md5($dimensi) ?>" 
																 aria-expanded="false" 
																 aria-controls="dimension-<?= md5($dimensi) ?>">
																<div class="dimension-info">
																	<h4 class="dimension-title">
																		<i class="fas fa-chevron-right collapse-icon"></i>
																		<?= htmlspecialchars($dimensi) ?>
																	</h4>
																	<div class="dimension-summary">																		<div class="summary-item">
																			<span class="summary-label">Skor Dimensi:</span>
																			<span class="summary-value dimension-score" data-dimension="<?= htmlspecialchars($dimensi) ?>">-</span>
																			<span class="summary-max">/ 5.00</span>
																			<i class="fas fa-question-circle tooltip-icon" style="display: none; cursor: help; margin-left: 8px;"></i>
																		</div>																		<div class="summary-item">
																			<span class="summary-label">Progress Dimensi:</span>
																			<button class="hierarchical-check-all-btn dimension-check-all" 
																					data-level="dimension" 
																					data-dimension="<?= htmlspecialchars($dimensi) ?>"
																					title="Check/Uncheck All Items in This Dimension">
																				<i class="fas fa-check-double"></i>
																			</button>
																			<button class="improvement-btn btn btn-sm btn-outline-info" 
																					data-scope-type="dimension" 
																					data-scope-name="<?= htmlspecialchars($dimensi) ?>"
																					title="Show Areas of Improvement">
																				<i class="fas fa-magnifying-glass-chart"></i>
																			</button>
																			<div class="summary-progress">
																				<div class="progress dimension-progress">
																					<div class="progress-bar" 
																						 role="progressbar" 
																						 style="width: 0%" 
																						 data-dimension="<?= htmlspecialchars($dimensi) ?>"
																						 aria-valuenow="0" 
																						 aria-valuemin="0" 
																						 aria-valuemax="100">
																					</div>
																				</div>
																				<small class="progress-text dimension-progress-text" data-dimension="<?= htmlspecialchars($dimensi) ?>">0%</small>
																			</div>
																		</div><div class="summary-item">
																			<span class="summary-label">Parameter:</span>
																			<span class="summary-value">
																				<?= $dimension_data['total_parameters'] ?>
																				<?php if (!empty($dimension_data['parameter_numbers'])): ?>
																					<?php
																					$count = count($dimension_data['parameter_numbers']);
																					if ($count === 1):
																					?>
																						(No. <?= $dimension_data['parameter_numbers'][0] ?>)
																					<?php else: ?>
																						(No. <?= min($dimension_data['parameter_numbers']) ?> - <?= max($dimension_data['parameter_numbers']) ?>)
																					<?php endif; ?>
																				<?php endif; ?>
																			</span>
																		</div>
																	</div>
																</div>
															</div>
															
															<!-- Level 2: Sub-dimensions -->
															<div class="collapse rmi-subdimensions-level" id="dimension-<?= md5($dimensi) ?>" data-parent=".rmi-dimensions-level">
																<div class="subdimensions-container">
																	<?php foreach ($dimension_data['sub_dimensions'] as $sub_dimensi => $subdim_data): ?>
																		<div class="rmi-subdimension-card" data-dimension="<?= htmlspecialchars($dimensi) ?>" data-subdimension="<?= htmlspecialchars($sub_dimensi) ?>">
																			<div class="subdimension-header" role="button" data-toggle="collapse" 
																				 data-target="#subdimension-<?= md5($dimensi . $sub_dimensi) ?>" 
																				 aria-expanded="false" 
																				 aria-controls="subdimension-<?= md5($dimensi . $sub_dimensi) ?>">
																				<div class="subdimension-info">
																					<h5 class="subdimension-title">
																						<i class="fas fa-chevron-right collapse-icon"></i>
																						<?= htmlspecialchars($sub_dimensi) ?>
																					</h5>
																					<div class="subdimension-summary">																						<div class="summary-item">
																							<span class="summary-label">Skor Subdimensi:</span>
																							<span class="summary-value subdimension-score" data-dimension="<?= htmlspecialchars($dimensi) ?>" data-subdimension="<?= htmlspecialchars($sub_dimensi) ?>">-</span>
																							<span class="summary-max">/ 5.00</span>
																							<i class="fas fa-question-circle tooltip-icon" style="display: none; cursor: help; margin-left: 8px;"></i>
																						</div>																						<div class="summary-item">
																							<span class="summary-label">Progress Subdimensi:</span>
																							<button class="hierarchical-check-all-btn subdimension-check-all" 
																					data-level="subdimension" 
																					data-dimension="<?= htmlspecialchars($dimensi) ?>"
																					data-subdimension="<?= htmlspecialchars($sub_dimensi) ?>"
																					title="Check/Uncheck All Items in This Sub-dimension">
																				<i class="fas fa-check-double"></i>
																			</button>
																			<button class="improvement-btn btn btn-sm btn-outline-info" 
																					data-scope-type="subdimension" 
																					data-scope-name="<?= htmlspecialchars($sub_dimensi) ?>"
																					data-parent-dimension="<?= htmlspecialchars($dimensi) ?>"
																					title="Show Areas of Improvement">
																				<i class="fas fa-magnifying-glass-chart"></i>
																			</button>
																							<div class="summary-progress">
																								<div class="progress subdimension-progress">
																									<div class="progress-bar" 
																										 role="progressbar" 
																										 style="width: 0%" 
																										 data-dimension="<?= htmlspecialchars($dimensi) ?>"
																										 data-subdimension="<?= htmlspecialchars($sub_dimensi) ?>"
																										 aria-valuenow="0" 
																										 aria-valuemin="0" 
																										 aria-valuemax="100">
																									</div>
																								</div>
																								<small class="progress-text subdimension-progress-text" data-dimension="<?= htmlspecialchars($dimensi) ?>" data-subdimension="<?= htmlspecialchars($sub_dimensi) ?>">0%</small>
																							</div>
																						</div><div class="summary-item">
																							<span class="summary-label">Parameter:</span>
																							<span class="summary-value">
																								<?= $subdim_data['parameter_count'] ?>
																								<?php if (!empty($subdim_data['parameter_numbers'])): ?>
																									<?php
																									$count = count($subdim_data['parameter_numbers']);
																									if ($count === 1):
																									?>
																										(No. <?= $subdim_data['parameter_numbers'][0] ?>)
																									<?php else: ?>
																										(No. <?= min($subdim_data['parameter_numbers']) ?> - <?= max($subdim_data['parameter_numbers']) ?>)
																									<?php endif; ?>
																								<?php endif; ?>
																							</span>
																						</div>
																					</div>
																				</div>
																			</div>
																			
																			<!-- Level 3: Parameters -->
																			<div class="collapse rmi-parameters-level" id="subdimension-<?= md5($dimensi . $sub_dimensi) ?>" data-parent=".rmi-subdimensions-level">
																				<div class="parameters-container">
																					<?php foreach ($rmi_data[$dimensi][$sub_dimensi] as $param_data): ?>
																						<?php 
																						$parameter_key = md5($dimensi . $sub_dimensi . $param_data['parameter']);
																						$total_points_count = isset($total_points[$parameter_key]) ? $total_points[$parameter_key] : 0;
																						?>
																						
																						<!-- Parameter Card (existing structure) -->
																						<div class="card rmi-parameter-card mb-4" data-parameter="<?= $parameter_key ?>" data-total-points="<?= $total_points_count ?>">															<div class="card-header">
																								<div class="d-flex justify-content-between align-items-center">
																									<div>
																										<small class="text-muted dimensi-breadcrumb">
																											<?= htmlspecialchars($dimensi) ?> > <?= htmlspecialchars($sub_dimensi) ?>
																										</small>
																										<h5 class="mb-0 parameter-title">
																											<?= htmlspecialchars($param_data['parameter']) ?>
																										</h5>
																									</div>
																									<div class="parameter-summary-compact">
																										<!-- Compact Progress and Score Area -->
																										<div class="progress-score-area">
																											<div class="progress-wrapper">																												<div class="d-flex justify-content-between align-items-center mb-1">
																													<small class="text-muted">Progress Parameter</small>
																													<button class="hierarchical-check-all-btn parameter-check-all" 
																															data-level="parameter" 
																															data-parameter="<?= $parameter_key ?>"
																															title="Check/Uncheck All Items in This Parameter">
																														<i class="fas fa-check-double"></i>
																													</button>
																													<small class="progress-text" data-parameter="<?= $parameter_key ?>">
																														0/<?= $total_points_count ?> (0%)
																													</small>
																												</div>
																												<div class="progress" style="height: 8px;">
																													<div class="progress-bar bg-success" 
																														 role="progressbar" 
																														 style="width: 0%" 
																														 data-parameter="<?= $parameter_key ?>"
																														 aria-valuenow="0" 
																														 aria-valuemin="0" 
																														 aria-valuemax="100">
																													</div>
																												</div>
																											</div>
																											<div class="parameter-score-display">
																												<div class="score-label">Skor Parameter</div>
																												<div class="score-value" data-parameter="<?= $parameter_key ?>">0</div>
																												<div class="score-max">/ 5</div>
																											</div>
																										</div>
																										<div class="parameter-status-badge ml-3">
																											<span class="badge badge-secondary parameter-phase-badge" data-parameter="<?= $parameter_key ?>">
																												Fase 0
																											</span>
																										</div>
																									</div>
																								</div>
																							</div>
																<div class="card-body">
																								<!-- Accordion for Phases (existing structure) -->
																								<div class="accordion rmi-phases-container" id="accordion_<?= $parameter_key ?>" data-parameter="<?= $parameter_key ?>">
																									<?php for ($phase = 1; $phase <= 5; $phase++): ?>
																										<?php 
																										$phase_content = $param_data['phases'][$phase];
																										$phase_points = explode('•', $phase_content);
																										$phase_points = array_filter(array_map('trim', $phase_points));
																										$collapse_id = "collapse_" . $parameter_key . "_phase_" . $phase;
																										$heading_id = "heading_" . $parameter_key . "_phase_" . $phase;
																										$is_first_phase = ($phase == 1);
																										?>
																										
																										<?php if (!empty($phase_points)): ?>
																											<!-- Phase Card -->
																											<div class="card rmi-phase-wrapper mb-2" data-phase="<?= $phase ?>">
																												<!-- Phase Header -->
																												<div class="card-header" id="<?= $heading_id ?>">
																													<h6 class="mb-0">
																														<button class="btn btn-link rmi-phase-header w-100 text-left <?= $is_first_phase ? '' : 'collapsed' ?>" 
																																type="button" 
																																data-toggle="collapse" 
																																data-target="#<?= $collapse_id ?>" 
																																aria-expanded="<?= $is_first_phase ? 'true' : 'false' ?>" 
																																aria-controls="<?= $collapse_id ?>">
																															<div class="d-flex align-items-center justify-content-between w-100">																																<div class="rmi-phase-title d-flex align-items-center">
																																	<span class="phase-check-icon mr-2" style="display: none;">
																																		<i class="fas fa-check-circle text-success"></i>
																																	</span>
																																	<span class="phase-number"><?= $phase ?></span>
																																	<span class="phase-name ml-2"><?php
																						$phase_names = [
																							1 => 'Fase Awal',
																							2 => 'Fase Berkembang', 
																							3 => 'Fase Praktik yang Baik',
																							4 => 'Fase Praktik yang Lebih Baik',
																							5 => 'Fase Praktik Terbaik'
																						];
																						echo $phase_names[$phase];
																						?></span>
																																</div>
																																<div class="d-flex align-items-center">
																																	<!-- Master Check All/Uncheck All Checkbox -->
																																	<div class="phase-check-all-container mr-3" title="Check/Uncheck All Items in This Phase">
																																		<input type="checkbox" 
																																			   class="phase-check-all" 
																																			   id="checkall_<?= $parameter_key ?>_<?= $phase ?>"
																																			   data-parameter-key="<?= $parameter_key ?>"
																																			   data-phase-level="<?= $phase ?>">
																																		<label for="checkall_<?= $parameter_key ?>_<?= $phase ?>" class="mb-0 ml-1">
																																			<small class="text-muted">All</small>
																																		</label>
																																	</div>
																																	<small class="text-muted phase-progress mr-2">
																																		<span class="completed-count">0</span>/<span class="total-count"><?= count($phase_points) ?></span>
																																	</small>
																																	<i class="fa fa-chevron-down toggle-icon"></i>
																																</div>
																															</div>
																														</button>
																													</h6>
																												</div>
																												
																												<!-- Phase Content -->
																												<div id="<?= $collapse_id ?>" 
																													 class="collapse <?= $is_first_phase ? 'show' : '' ?>" 
																													 aria-labelledby="<?= $heading_id ?>" 
																													 data-parent="#accordion_<?= $parameter_key ?>">
																													<div class="card-body rmi-phase-content">
																														<div class="rmi-checklist-container">
																															<?php foreach ($phase_points as $index => $point): ?>
																																<?php if (!empty($point)): ?>
																																	<?php 
																																	$point_id = md5($point);
																																	$checkbox_id = $parameter_key . '_' . $phase . '_' . $point_id;
																																	?>
																																	<div class="rmi-checklist-item">
																																		<div class="custom-control custom-checkbox">
																																			<input class="custom-control-input rmi-checkbox" 
																																				   type="checkbox" 
																																				   id="<?= $checkbox_id ?>"
																																				   data-parameter="<?= $parameter_key ?>"
																																				   data-phase="<?= $phase ?>"
																																				   data-point="<?= $point_id ?>"
																																				   data-point-text="<?= htmlspecialchars($point) ?>">
																																			<label class="custom-control-label" for="<?= $checkbox_id ?>">
																																				<?= htmlspecialchars($point) ?>
																																			</label>
																																		</div>
																																	</div>
																																<?php endif; ?>
																															<?php endforeach; ?>
																														</div>
																													</div>
																												</div>
																											</div>
																										<?php endif; ?>
																									<?php endfor; ?>
																								</div>
																							</div>
																						</div>
																					<?php endforeach; ?>
																				</div>
																			</div>
																		</div>
																	<?php endforeach; ?>
																</div>
															</div>
														</div>
													<?php endforeach; ?>
												</div>											<?php else: ?>
												<div class="alert alert-info text-center">
													<i class="icon-info mr-2"></i>
													No RMI data available. Please check if the CSV file exists.
												</div>
											<?php endif; ?>										</div>
										
										<!-- Skor Aspek Kinerja Section -->
										<div class="rmi-performance-section mt-5">
											<h2 class="mb-4">Aspek Kinerja RMI</h2>
											<div class="card rmi-performance-card">
												<div class="card-body">
													<div class="table-responsive">
														<table class="table table-bordered rmi-performance-table">
															<thead class="thead-light">
																<tr>
																	<th style="width: 30%;">Aspek</th>
																	<th style="width: 20%;">Nilai Aspek</th>
																	<th style="width: 15%;">Nilai Konversi</th>
																	<th style="width: 15%;">Bobot (%)</th>
																	<th style="width: 20%;">Nilai Konversi x Bobot</th>
																</tr>
															</thead>
															<tbody>
																<!-- Row 1: Tingkat Kesehatan Peringkat Akhir -->
																<tr>
																	<td>																		<div class="d-flex align-items-center">
																			<span>Tingkat Kesehatan Peringkat Akhir (Final Rating)</span>																			<button type="button" class="btn btn-sm btn-outline-info ml-2 tooltip-trigger" 
																					data-toggle="popover" 
																					data-placement="top" 
																					data-trigger="click"
																					data-html="true"
																					title="Tabel Konversi Final Rating">
																				<i class="fas fa-info-circle"></i>
																			</button>
																		</div>
																	</td>
																	<td>
																		<select class="form-control performance-aspect-select" id="finalRatingSelect" data-aspect="finalRating">
																			<option value="">Pilih...</option>
																			<option value="AAA">AAA</option>
																			<option value="AA">AA</option>
																			<option value="A">A</option>
																			<option value="BBB">BBB</option>
																			<option value="BB">BB</option>
																			<option value="B">B</option>
																			<option value="CCC">CCC</option>
																			<option value="CC">CC</option>
																			<option value="C">C</option>
																		</select>
																	</td>
																	<td>
																		<input type="text" class="form-control performance-conversion" id="finalRatingConversion" readonly>
																	</td>
																	<td>
																		<input type="number" class="form-control performance-weight" id="finalRatingWeight" value="50" min="0" max="100">
																	</td>
																	<td>
																		<input type="text" class="form-control performance-weighted-value" id="finalRatingWeightedValue" readonly>
																	</td>
																</tr>
																
																<!-- Row 2: Peringkat Komposit Risiko -->
																<tr>
																	<td>																		<div class="d-flex align-items-center">
																			<span>Peringkat Komposit Risiko</span>																			<button type="button" class="btn btn-sm btn-outline-info ml-2 tooltip-trigger" 
																					data-toggle="popover" 
																					data-placement="top" 
																					data-trigger="click"
																					data-html="true"
																					title="Tabel Konversi Peringkat Komposit Risiko">
																				<i class="fas fa-info-circle"></i>
																			</button>
																		</div>
																	</td>
																	<td>
																		<select class="form-control performance-aspect-select" id="riskRatingSelect" data-aspect="riskRating">
																			<option value="">Pilih...</option>
																			<option value="1">1</option>
																			<option value="2">2</option>
																			<option value="3">3</option>
																			<option value="4">4</option>
																			<option value="5">5</option>
																		</select>
																	</td>
																	<td>
																		<input type="text" class="form-control performance-conversion" id="riskRatingConversion" readonly>
																	</td>
																	<td>
																		<input type="number" class="form-control performance-weight" id="riskRatingWeight" value="50" min="0" max="100">
																	</td>
																	<td>
																		<input type="text" class="form-control performance-weighted-value" id="riskRatingWeightedValue" readonly>
																	</td>																</tr>
															</tbody>														</table>
													</div>
												</div>
											</div>
										</div>
										
										<!-- Unified Final Summary Container -->
										<div class="rmi-final-summary-container mt-5">
											<div class="row">
												<!-- Total Skor Aspek Dimensi Column -->
												<div class="col-lg-6 mb-4">
													<div class="rmi-dimension-total-container">
														<div class="card h-100">
															<div class="card-body d-flex justify-content-between align-items-center">
																<h5 class="mb-0 font-weight-bold">Total Skor Aspek Dimensi:</h5>
																<input type="text" class="form-control font-weight-bold text-center" style="max-width: 180px;" id="totalDimensionScore" readonly value="-.-- / 5.00">
															</div>
														</div>
													</div>
												</div>
												
												<!-- Total Skor Aspek Kinerja Column -->
												<div class="col-lg-6 mb-4">
													<div class="rmi-performance-total-container">
														<div class="card h-100">
															<div class="card-body d-flex justify-content-between align-items-center">
																<h5 class="mb-0 font-weight-bold">Total Skor Aspek Kinerja:</h5>
																<input type="text" class="form-control font-weight-bold text-center" style="max-width: 180px;" id="totalPerformanceScore" readonly value="0.00">
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div><!-- Laporan Tab -->
									<div class="tab-pane" id="laporan" role="tabpanel" aria-labelledby="laporan-tab">
										<h2 class="mb-4">AOI RMI</h2>
										<p class="text-muted">AOI RMI akan ditampilkan di sini pada tahap selanjutnya.</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</main>

<!-- Area of Improvement Modal -->
<div class="modal fade" id="improvementModal" tabindex="-1" role="dialog" aria-labelledby="improvementModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header" id="improvementModalHeader">
				<h5 class="modal-title" id="improvementModalLabel">Area of Improvement</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<!-- Content will be populated by JavaScript -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>