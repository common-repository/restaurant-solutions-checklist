(function($) {
  "use strict";

  /**
   * All of the code for your public-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */
  jQuery(document).ready(function($) {
    populate_checklist();
    date_change();
    update_checklist();

    function date_change() {
      $(document).on("change", 'input[name="checklist_date"]', function() {
        populate_checklist();
      });
    }

    function populate_checklist() {
      var checklist_id = parseInt($("#checklist_id").val());
      var date = $("input[type='radio']:checked").val();
      var code = parseInt($("#code").val());
      var data = {
        checklist_id: checklist_id,
        date: date,
        code: code,
        action: "populate_checklist"
      };
      $.ajax({
        type: "post",
        dataType: "json",
        url: stp_ajax_params.ajax_url,
        data: data,
        success: function(response) {
          if (response.status == true) {
            apply_values(response);
          }
        }
      });
    }

    function apply_values(response) {
      var items = response["items"];
      $(".stp-checklist-item").prop("checked", false);
      $.each(items, function(key, value) {
        $("#" + key).prop("checked", true);
      });
    }

    function update_checklist() {
      $(document).on("change", ".stp-checklist-item", function() {
        var code = parseInt($("#code").val());
        var checklist_id = parseInt($("#checklist_id").val());
        var date = $("input[type='radio']:checked").val();
        var item_checked = {};
        var count = 0;
        $(".stp-checklist-item:checked").each(function() {
          item_checked[count] = { item: $(this).val() };
          count++;
        });
        var data = {
          code: code,
          checklist_id: checklist_id,
          items: item_checked,
          date: date,
          action: "update_checklist"
        };

        $.ajax({
          type: "post",
          dataType: "json",
          url: stp_ajax_params.ajax_url,
          data: data,
          success: function(response) {
            if (response.status == false) {
              error_msg(response.msg, "danger");
            }
          }
        });
      });
    }

    function error_msg(msg, type) {
      if (type == "success") {
        var class_name = "alert-success";
      } else {
        var class_name = "alert-danger";
      }
      $("#stp-error").html(
        '<div class="alert ' +
          class_name +
          '" role="alert">' +
          msg +
          '<button type="button" class="stp-close close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
      );

      $(document).on("click", ".stp-close", function(e) {
        $(this)
          .parent()
          .remove();
      });
    }
  });
})(jQuery);
