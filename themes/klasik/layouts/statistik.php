<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript">
	let chart;
	const rawData = Object.values(<?= json_encode($stat) ?>);
	const type = '<?= $tipe == 1 ? 'column' : 'pie' ?>';
	const legend = Boolean(!<?= ($tipe) ?>);
	let categories = [];
	let data = [];
	let i = 1;
	let status_tampilkan = true;
	for (const stat of rawData) {
		if (stat.nama !== 'BELUM MENGISI' && stat.nama !== 'TOTAL' && stat.nama !== 'JUMLAH' && stat.nama != 'PENERIMA' && stat.nama != 'BUKAN PENERIMA') {
			let filteredData = [stat.nama, parseInt(stat.jumlah)];
			categories.push(i);
			data.push(filteredData);
			i++;
		}
	}

	function tampilkan_nol(tampilkan = false) {
		if (tampilkan) {
			$(".nol").parent().show();
		} else {
			$(".nol").parent().hide();
		}
	}

	function toggle_tampilkan() {
		$('#showData').click();
		tampilkan_nol(status_tampilkan);
		status_tampilkan = !status_tampilkan;
		if (status_tampilkan) $('#tampilkan').text('Tampilkan Nol');
		else $('#tampilkan').text('Sembunyikan Nol');
	}

	$(document).ready(function () {
		tampilkan_nol(false);
		chart = new Highcharts.Chart({
			chart: {
				renderTo: 'container'
			},
			title: 0,
			xAxis: {
				categories: categories,
			},
			plotOptions: {
				series: {
					colorByPoint: true
				},
				column: {
					pointPadding: -0.1,
					borderWidth: 0
				},
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					showInLegend: true
				}
			},
			legend: {
				enabled: legend
			},
			series: [{
				type: type,
				name: 'Jumlah Populasi',
				shadow: 1,
				border: 1,
				data: data
			}]
		});

		$('#showData').click(function () {
			$('tr.lebih').show();
			$('#showData').hide();
			tampilkan_nol(false);
		});

	});
</script>
<style>
	tr.lebih {
		display: none;
	}
</style>

<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">Grafik <?= $heading ?></h3>
		<div class="box-tools pull-right">
			<div class="btn-group-xs">
				<a href="<?= site_url("first/statistik/$st/1") ?>" class="btn <?= ($tipe==1) ? 'btn-primary' : 'btn-default' ?> btn-xs">Bar Graph</a>
				<a href="<?= site_url("first/statistik/$st/0") ?>" class="btn <?= ($tipe==0) ? 'btn-primary' : 'btn-default' ?> btn-xs">Pie Cart</a>
			</div>
		</div>
	</div>
	<div class="box-body">
		<div id="container"></div>
		<div id="contentpane">
			<div class="ui-layout-north panel top"></div>
		</div>
	</div>
</div>

<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">Tabel <?= $heading ?></h3>
	</div>
	<div class="box-body">
		<div class="table-responsive">
		<table class="table table-striped">
			<thead>
			<tr>
				<th rowspan="2">No</th>
				<th rowspan="2" style='text-align:left;'>Kelompok</th>
				<th colspan="2">Jumlah</th>
				<?php if ($jenis_laporan == 'penduduk'):?>
					<th colspan="2">Laki-laki</th>
					<th colspan="2">Perempuan</th>
				<?php endif;?>
			</tr>
			<tr>
				<th style='text-align:right'>n</th><th style='text-align:right'>%</th>
				<?php if ($jenis_laporan == 'penduduk'):?>
					<th style='text-align:right'>n</th><th style='text-align:right'>%</th>
					<th style='text-align:right'>n</th><th style='text-align:right'>%</th>
				<?php endif;?>
			</tr>
			</thead>
			<tbody>
				<?php $i=0; $l=0; $p=0; $hide=""; $h=0; $jm1=1; $jm = count($stat);?>
				<?php foreach ($stat as $data):?>
					<?php $jm1++; ?>
					<?php $h++; ?>
					<?php if ($h > 12 AND $jm > 10): ?>
						<?php $hide = "lebih"; ?>
					<?php endif;?>
					<tr class="<?=$hide?>">
						<td class="angka">
							<?php if ($jm1 > $jm - 2):?>
								<?=$data['no']?>
							<?php else:?>
								<?=$h?>
							<?php endif;?>
						</td>
						<td><?=$data['nama']?></td>
						<td class="angka <?php ($jm1 <= $jm - 2) and ($data['jumlah'] == 0) and print('nol')?>"><?=$data['jumlah']?></td>
						<td class="angka"><?=$data['persen']?></td>
						<?php if ($jenis_laporan == 'penduduk'):?>
							<td class="angka"><?=$data['laki']?></td>
							<td class="angka"><?=$data['persen1']?></td>
							<td class="angka"><?=$data['perempuan']?></td>
							<td class="angka"><?=$data['persen2']?></td>
						<?php endif;?>
					</tr>
					<?php $i += $data['jumlah'];?>
					<?php $l += $data['laki'];?>
					<?php $p += $data['perempuan'];?>
				<?php endforeach;?>
			</tbody>
		</table>
		<?php if ($hide=="lebih"):?>
			<div style='float: left;'>
				<button class='uibutton special' id='showData'>Selengkapnya...</button>
			</div>
		<?php endif;?>
		<div style="float: right;">
			<button id='tampilkan' onclick="toggle_tampilkan();" class="uibutton special">Tampilkan Nol</button>
		</div>
	</div>
	</div>
</div>

<?php if ($program_peserta):?>
	<div class="box box-danger">
		<div class="box-header with-border">
			<h3 class="box-title">Daftar Peserta Program <?=$heading?></h3>
		</div>
		<div class="box-body">
			<?php $peserta = $program_peserta[1];?>
			<div class="table-responsive">
				<table class="table table-bordered table-striped dataTable table-hover">
					<thead class="bg-gray disabled color-palette">
						<tr>
							<th rowspan="2" class="text-center">No</th>
							<th rowspan="2" nowrap class="text-center">Program</th>
							<th rowspan="2" nowrap class="text-center">Nama</th>
							<th rowspan="2" class="text-center">Alamat</th>
						</tr>
					</thead>
					<tbody>
						<?php $nomer = $paging->offset;?>
						<?php if (is_array($peserta)): ?>
							<?php foreach ($peserta as $key=>$item): $nomer++;?>
								<tr>
									<td class="text-center"><?= $nomer?></td>
									<td><?= strtoupper($item['program_plus']);?></td>
									<td nowrap><?= $item["peserta_info"]?></td>
									<td nowrap><?= $item["info"];?></td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php endif;?>
