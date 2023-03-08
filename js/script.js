//binding daterangepicker on experience and education duration fields
bindDateRangePicker($('input[name="expr_duration[]"]'));
bindDateRangePicker($('input[name="educ_duration[]"]'));

//binding click event on remove_field class's button
bindRemoveField();

//adding functinality of present in experience and education duration
bindPresentCheck();

//function to bind daterangepicker without auto update
function bindDateRangePicker(element) {
  element.daterangepicker({
    autoUpdateInput: false,
    locale: {
      cancelLabel: "Clear",
    },
  });
  element.on("apply.daterangepicker", function (ev, picker) {
    $(this).val(
      picker.startDate.format("MM/DD/YYYY") +
        " - " +
        picker.endDate.format("MM/DD/YYYY")
    );
  });

  element.on("cancel.daterangepicker", function (ev, picker) {
    $(this).val("");
  });
}

//function to bind click event and remove field
function bindRemoveField() {
  $(".remove_field").click(function () {
    //removing parent div of remove_field class' button
    $(this).parent().remove();
  });
}

//function to bind present check on duration field
function bindPresentCheck() {
  $(".present_check").change(function () {
    let durationElement = $(this)
      .parent()
      .children('input[name*="duration[]"]');
    if (this.checked && durationElement.val() != "") {
      let duration = durationElement.val().split("-");
      durationElement.val(duration[0] + "- present");
    } else {
      durationElement.val("");
    }
  });
}

//function to add professional fields
function fieldAdder(className, beforeElementId) {
  let lastField = $("." + className).last();
  let newField = lastField.clone(false);
  bindDateRangePicker(newField.children('input[name="expr_duration[]"]'));
  bindDateRangePicker(newField.children('input[name="educ_duration[]"]'));
  newField.insertBefore("#" + beforeElementId);
  bindRemoveField();
  bindPresentCheck();
}