function pi_new_field(
  stored_value_array,
  list_container,
  tmpl,
  add_btn,
  default_data,
  remove_row
) {
  this.stored_value_array = stored_value_array;
  this.list_container = list_container;
  this.tmpl = tmpl;
  this.add_btn = add_btn;
  this.default_data = default_data;
  this.remove_row = remove_row;
  /**
   * populating based on stored value
   */
  if (typeof window[this.stored_value_array] != "undefined") {
    this.count =
      window[this.stored_value_array].length == 0
        ? 0
        : window[this.stored_value_array].length;

    jQuery("#" + this.list_container).append(
      jQuery("#" + this.tmpl).render(
        window[this.stored_value_array].map(function(val, index) {
          console.log({ count: index, value: val });
          return { count: index, value: val };
        })
      )
    );
  }

  /**
   * Adding
   */
  var $ = jQuery;

  $("#" + this.add_btn).click(() => {
    var data = {
      count: this.count,
      value: this.default_data
    };
    var template = $.templates("#" + this.tmpl);
    var htmlOutput = template.render(data);
    $("#" + this.list_container).append(htmlOutput);
    this.count++;
  });

  /**
   * Removing
   */
  $(document).on("click", "." + this.remove_row, e => {
    var selected = $(e.currentTarget).parent();
    selected.remove();
    /*this.count--;**/
  });
}

jQuery(document).ready(function($) {
  new pi_new_field(
    "checklist",
    "checklist-form",
    "checklist-template",
    "add_item",
    {
      item: ""
    },
    "stp-remove"
  );
});
