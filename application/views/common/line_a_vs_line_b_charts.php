<script>
	$(function()
	{
		//------------------------------------------------------------------------------------------------------------------
		//-- Quantity This Year Vs Last Year Chart --
		//------------------------------------------------------------------------------------------------------------------


	});
</script>

<script>
	//Display the correct graph and legend when menu selection made.
	$(function() {

		//Set the default value of the choose-graph select dropdown
		$("#choose-graph").val("this-year-vs-target-option");

		//Hide all the charts except the this-year-vs-target chart
		$("#quantity-this-year-vs-last-year").hide();
		$("#quantity-this-year-cml-vs-target-cml").hide();

		$("#choose-graph").on('change', function() {
			//Hide all graphs and legends.
			$("#threeyearsaleschart .chart canvas").hide();
			$("#threeyearsaleschart .chart-legend li").hide();

			//Unhide the selected graph and legend.
			if ($(this).val() == "this-year-vs-target-option") {
				$("#this-year-vs-target").show();
				$("#this-year-legend").show();
				$("#target-legend").show();
			}
			else if ($(this).val() == "this-year-cml-vs-target-cml-option") {
				$("#this-year-cml-vs-target-cml").show();
				$("#this-year-cml-legend").show();
				$("#target-cml-legend").show();
			}
			else if ($(this).val() == "this-year-vs-last-year-option") {
				$("#this-year-vs-last-year").show();
				$("#this-year-legend").show();
				$("#last-year-legend").show();
				$("#before-year-legend").show();
			}
			else if ($(this).val() == "this-year-cml-vs-last-year-cml-option") {
				$("#this-year-cml-vs-last-year-cml").show();
				$("#this-year-cml-legend").show();
				$("#last-year-cml-legend").show();
				$("#before-year-cml-legend").show();
			}
			else if ($(this).val() == "quantity-this-year-vs-last-year-option") {
				$("#quantity-this-year-vs-last-year").show();
				$("#this-year-legend").show();
				$("#last-year-legend").show();
				$("#before-year-legend").show();
			}
			else if ($(this).val() == "quantity-this-year-cml-vs-target-cml-option") {
				$("#quantity-this-year-cml-vs-target-cml").show();
				$("#this-year-cml-legend").show();
				$("#last-year-cml-legend").show();
				$("#before-year-cml-legend").show();
			}
		});
	});
</script>