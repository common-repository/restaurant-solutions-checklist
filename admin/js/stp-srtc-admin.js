(function($) {
  "use strict";

  /**
   * All of the code for your admin-facing JavaScript source
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
  /* Create employee */
  $(function() {
    get_all_employee();

    $(document).on("click", ".stp-status", function(e) {
      var id = parseInt($(this).data("id"));
      $.ajax({
        type: "post",
        dataType: "json",
        url: stp_ajax_params.ajax_url,
        data: { action: "change_state", id: id },
        success: function(response) {
          if (response.status == true) {
            error_msg(response.msg, "success");
            get_all_employee();
          } else {
            error_msg(response.msg, "danger");
            get_all_employee();
          }
        }
      });
    });

    $(document).on("click", ".stp-delete", function(e) {
      var id = parseInt($(this).data("id"));
      $.ajax({
        type: "post",
        dataType: "json",
        url: stp_ajax_params.ajax_url,
        data: { action: "delete_employee", id: id },
        success: function(response) {
          if (response.status == true) {
            error_msg(response.msg, "success");
            get_all_employee();
          } else {
            error_msg(response.msg, "danger");
            get_all_employee();
          }
        }
      });
    });

    $(document).on("click", ".stp-edit", function(e) {
      var button = $(this);
      button.attr("disabled", "disabled");
      var id = button.data("id");
      var row = $("#employee_" + id);
      var name = row.data("name");
      var code = row.data("code");
      var tmpl = $.templates(
        '<tr id="edit_employee_{{:id}}"><td colspan="6" class="text-center"><form class="edit_employee"><div class="row"><div class="col-12 col-md-3"><input required type="text" name="name" value="{{:name}}" class="form-control"><input type="hidden" name="employee_id" value="{{:id}}" class="form-control"></div><div class="col-12 col-md-3"><input required name="code" value="{{:code}}" class="form-control"><input type="hidden" name="action" value="edit_employee" class="form-control"></div><div class="col-12 col-md-3"><button type="submit" class="btn btn-primary btn-block edit-submit">Submit</button></div><div class="col-12 col-md-3"><button type="button" class="btn btn-danger btn-block edit-cancel" data-id="{{:id}}">Cancel</button></div></div></form></td></tr>'
      );
      var html = tmpl.render({ name: name, code: code, id: id });
      row.after(html);
    });

    $(document).on("click", ".edit-cancel", function(e) {
      var id = $(this).data("id");
      var row = $("#edit_employee_" + id);
      row.remove();
      $("#employee_" + id + " .stp-edit").removeAttr("disabled");
    });

    $(document).on("submit", ".edit_employee", function(e) {
      e.preventDefault();
      $.ajax({
        type: "get",
        dataType: "json",
        url: stp_ajax_params.ajax_url,
        data: $(this).serialize(),
        success: function(response) {
          if (response.status == true) {
            error_msg(response.msg, "success");
            get_all_employee();
          } else {
            error_msg(response.msg, "danger");
            get_all_employee();
          }
        }
      });
    });

    $("#create_employee_form").on("submit", function(e) {
      e.preventDefault();
      var name = $("#add_name").val();
      var code = $("#add_code").val();
      $.ajax({
        type: "post",
        dataType: "json",
        url: stp_ajax_params.ajax_url,
        data: { action: "create_employee", name: name, code: code },
        success: function(response) {
          if (response.status == true) {
            error_msg(response.msg, "success");
            get_all_employee();
          } else {
            error_msg(response.msg, "danger");
          }
        }
      });
    });

    function get_all_employee() {
      $.ajax({
        type: "post",
        dataType: "json",
        url: stp_ajax_params.ajax_url,
        data: { action: "get_all_employee" },
        success: function(response) {
          create_table(response);
        }
      });
    }

    function create_table(data) {
      if (data.length > 0) {
        var tmpl = $.templates("#employee_template");
        var html = tmpl.render(data);
      } else {
        var tmpl = $.templates(
          '<tr><td colspan="6" class="text-center"><strong>{{: msg}}</strong></td></tr>'
        );
        var html = tmpl.render({ msg: "There are no employees to show" });
      }

      $("#employee_list").html(html);
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

    jQuery("#date").datepicker({
      dateFormat: "yy-mm-dd"
    });
  });
})(jQuery);
