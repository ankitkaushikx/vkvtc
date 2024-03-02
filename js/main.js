jQuery(document).ready(function ($) {
  console.log("JQuery Working");

  let search_form = $("#search-user-form");

  search_form.submit(function (e) {
    e.preventDefault();

    let search_term = $("#my-search-term").val();
    let formData = new FormData();
    formData.append("action", "my_search_function");
    formData.append("search_term", search_term);

    $.ajax({
      url: ajaxUrl, // Corrected from ajaxUrl
      type: "post",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        $("#my-table-result").html(response);
      },
      error: function (error) {
        console.log(error);
      },
    });
  });
});

console.log("dfdf"); // Outside the document ready block
