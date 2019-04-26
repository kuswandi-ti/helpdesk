<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('PATH_ASSET_APPS') ?>css/styles.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('PATH_ASSET_APPS') ?>css/vendor/bootstrap-table-expandable/bootstrap-table-expandable.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.2/css/fixedHeader.dataTables.min.css">
<style>
    .ui-autocomplete {
        z-index: 99999999 !important;
    }
	 /* Tooltip container */
	.tooltips {
		position: relative;
		
	}

	/* Tooltip text */
	.tooltips .ttext {
		visibility: hidden;
		width: 220px;
		background-color: #555;
		color: #fff;
		text-align: center;
		padding: 5px 0;
		border-radius: 6px;

		/* Position the tooltip text */
		position: absolute;
		z-index: 1;
		bottom: 125%;
		left: 50%;
		margin-left: -200%;

		/* Fade in tooltip */
		opacity: 0;
		transition: opacity 1s;
	}

	/* Tooltip arrow */
	.tooltips .ttext::after {
		content: "";
		position: absolute;
		top: 100%;
		left: 50%;
		margin-left: -5px;
		border-width: 5px;
		border-style: solid;
		border-color: #555 transparent transparent transparent;
	}

	/* Show the tooltip text when you mouse over the tooltip container */
	.tooltips:hover .ttext {
		visibility: visible;
		opacity: 1;
	} 
</style>