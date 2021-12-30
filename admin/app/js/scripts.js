$(document).ready(function () {
  let file_name_status = null,
    fileName = null,
    couponNumber = 1,
    allCouponData = [],
    edit_allCouponData = [];

  // Activate tooltip
  $('[data-toggle="tooltip"]').tooltip();

  // Select/Deselect checkboxes
  var checkbox = $('table tbody input[type="checkbox"]');
  $("#selectAll").click(function () {
    if (this.checked) {
      checkbox.each(function () {
        this.checked = true;
      });
    } else {
      checkbox.each(function () {
        this.checked = false;
      });
    }
  });
  checkbox.click(function () {
    if (!this.checked) {
      $("#selectAll").prop("checked", false);
    }
  });

  // File Name Validation
  $("#file_name").keyup(function () {
    var file_name = $(this).val().trim();
    if (file_name != "") {
      $.ajax({
        url: "file-list.php",
        type: "post",
        data: {
          file_name: file_name,
        },
        success: function (response) {
          $("#File_Name_Status").html(response);
          file_name_status = response;
        },
      });
    } else {
      $("#File_Name_Status").html("");
      file_name_status = "";
    }
  });

  // Create Page Form Submission
  $("#CreateFormSubmit").click(function () {
    //alert("Clicked");
    // alert($("#Coupon_code").val().serialize());
    if (
      $("#file_name").val() == "" ||
      $("#PAYMENT_URL").val() == "" ||
      $("#CORP_ID").val() == "" ||
      $("#API_USERNAME").val() == "" ||
      $("#API_PASSWORD").val() == "" ||
      $("#AGENT_ID").val() == "" ||
      $("#PAYMENT_TYPE").val() == "" ||
      $("#PRODUCT_CODE").val() == "" ||
      $("#PAYMENT_PROCESS").val() == "" ||
      $("#SOURCE").val() == "" ||
      $("#plan_name").val() == "" ||
      $("#plan_type").val() == "" ||
      $("#thank_you_page_url").val() == "" ||
      $("#Enrollment_Fee").val() == "" ||
      $("#Yearly_Payment_Fee").val() == "" ||
      $("#Monthly_Payment_Fee").val() == ""
    ) {
      alert("Fillup All Data");
    } else {
      var formData = {
        // File Name
        file_name: $("#file_name").val(),

        // API Settings
        PAYMENT_URL: $("#PAYMENT_URL").val(),
        CORP_ID: $("#CORP_ID").val(),
        API_USERNAME: $("#API_USERNAME").val(),
        API_PASSWORD: $("#API_PASSWORD").val(),
        AGENT_ID: $("#AGENT_ID").val(),
        PAYMENT_TYPE: $("#PAYMENT_TYPE").val(),
        PRODUCT_CODE: $("#PRODUCT_CODE").val(),
        PAYMENT_PROCESS: $("#PAYMENT_PROCESS").val(),
        SOURCE: $("#SOURCE").val(),

        // Plan Settings
        plan_name: $("#plan_name").val(),
        plan_type: $("#plan_type").val(),
        Yearly_payment_special: $("#Yearly_payment_special").val(),
        thank_you_page_url: $("#thank_you_page_url").val(),
        Enrollment_Fee: $("#Enrollment_Fee").val(),
        Yearly_Payment_Fee: $("#Yearly_Payment_Fee").val(),
        Monthly_Payment_Fee: $("#Monthly_Payment_Fee").val(),

        // Coupons
        CouponData: allCouponData,
      };

      // alert(formData.Enrollment_Fee);
      $.ajax({
        type: "POST",
        url: "create.php",
        data: formData,
        //dataType: "json",
        success: function (data) {
          location.reload();
          alert(data);
          // if (data != "File Name Not Abailable")
          // location.reload();
          //$('#content').html(data);
        },
      });
    }
  });

  // Coupon Add
  $("#addCouponToList").click(function () {
    var couponData = {
      Coupon_code: $("#Coupon_code").val(),
      Coupon_value: $("#Coupon_value").val(),
      Coupon_type: $("#Coupon_type").val(),
      coupon_message: $("#coupon_message").val(),
    };
    allCouponData.push(couponData);

    if (
      couponData.Coupon_code == "" ||
      couponData.Coupon_value == "" ||
      couponData.Coupon_type == "" ||
      couponData.coupon_message == ""
    ) {
      alert("Add Coupon Data");
    } else {
      var CouponRow = " ";
      CouponRow += '<tr id="row' + couponNumber + '">';
      CouponRow += "<td>" + couponData.Coupon_code + "</td>";
      CouponRow += "<td>" + couponData.Coupon_value + "</td>";
      CouponRow += "<td>" + couponData.Coupon_type + "</td>";
      CouponRow += "<td>" + couponData.coupon_message + "</td>";
      CouponRow += "<td>";
      CouponRow +=
        '<button id="' +
        couponNumber +
        '" class = "btn btn-danger btn_remove" > ';
      CouponRow += "Delete";
      CouponRow += "</button>";
      CouponRow += "</td>";
      CouponRow += "</tr>";

      tableBody = $("#coupon_list");
      tableBody.append(CouponRow);

      couponNumber++;
    }
  });

  // remove row
  $(document).on("click", ".btn_remove", function () {
    // alert("clicked");
    var button_id = $(this).attr("id");
    $("#row" + button_id + "").remove();
  });

  // File View
  $(".viewFile").click(function () {
    const file_id = this.id;
    const url = `file-info.php?id=${file_id}`;
    //alert(url);
    // alert(url);
    $.getJSON(url, function (data, status) {
      // alert(data.CORP_ID);
      var current_page = window.location.href;

      var URL = current_page.replace("admin/app/index.php", data.file_name);
      //`http://localhost/upwork/checkout/${data.file_name}.php`;
      //alert(data.monthly_payment_fee);
      URL += ".php";

      $("#view_PAGE_URL").html(URL);
      $("#view_File_Name").html(data.file_name + ".php");

      // alert(data.API_PASSWORD);
      // API Settings
      $("#view_PAYMENT_URL").html(data.PAYMENT_URL);
      $("#view_CORP_ID").html(data.CORP_ID);
      $("#view_API_USERNAME").html(data.API_USERNAME);
      $("#view_API_PASSWORD").html(data.API_PASSWORD);
      $("#view_AGENT_ID").html(data.AGENT_ID);
      $("#view_PAYMENT_TYPE").html(data.PAYMENT_TYPE);
      $("#view_PRODUCT_CODE").html(data.PRODUCT_CODE);
      $("#view_PAYMENT_PROCESS").html(data.PAYMENT_PROCESS);
      $("#view_SOURCE").html(data.SOURCE);

      // Plan Settings
      $("#view_plan_name").html(data.plan_name);
      $("#view_plan_type").html(data.plan_type);
      $("#view_Yearly_payment_special").html(data.Yearly_payment_special);
      $("#view_thank_you_page_url").html(data.thank_you_page_url);
      $("#view_Enrollment_Fee").html(data.Enrollment_Fee);

      $("#view_Yearly_Payment_Fee").html(data.Yearly_Payment_Fee);
      $("#view_Monthly_Payment_Fee").html(data.Monthly_Payment_Fee);
      // $('#edit_Enrollment_Fee').val(data.enrollment_fee);

      showCouponsData(file_id, "#view_coupon_list", true);
    });
  });

  // Display Data to Edit FIle
  $(".editFile").click(function () {
    const file_id = this.id;
    const url = `file-info.php?id=${file_id}`;
    $.getJSON(url, function (data, status) {
      // alert(data.Yearly_Payment_fee);
      // File Name
      previousFileName = data.file_name;
      $("#edit_file_name").val(data.file_name);
      $("#edit_file_id").val(data.file_id);

      // API Settings
      $("#edit_PAYMENT_URL").val(data.PAYMENT_URL);
      $("#edit_CORP_ID").val(data.CORP_ID);
      $("#edit_API_USERNAME").val(data.API_USERNAME);
      $("#edit_API_PASSWORD").val(data.API_PASSWORD);
      $("#edit_AGENT_ID").val(data.AGENT_ID);
      $("#edit_PAYMENT_TYPE").val(data.PAYMENT_TYPE);
      $("#edit_PRODUCT_CODE").val(data.PRODUCT_CODE);
      $("#edit_PAYMENT_PROCESS").val(data.PAYMENT_PROCESS);
      $("#edit_SOURCE").val(data.SOURCE);

      // Plan Settings
      $("#edit_plan_name").val(data.plan_name);
      $("#edit_plan_type").val(data.plan_type);
      $("#edit_Yearly_payment_special").val(data.Yearly_payment_special);
      $("#edit_thank_you_page_url").val(data.thank_you_page_url);
      $("#edit_Enrollment_Fee").val(data.Enrollment_Fee);

      $("#edit_Yearly_Payment_Fee").val(data.Yearly_Payment_Fee);
      $("#edit_Monthly_Payment_Fee").val(data.Monthly_Payment_Fee);
      // $('#edit_Enrollment_Fee').val(data.enrollment_fee);

      showCouponsData(file_id, "#edit_coupon_list", false);
    });
  });

  // Edit File Name Validation
  $("#edit_file_name").change(function () {
    var file_name = $(this).val().trim();
    if (file_name != "" && file_name != previousFileName) {
      $.ajax({
        url: "file-list.php",
        type: "post",
        data: {
          file_name: file_name,
        },
        success: function (response) {
          $("#edit_File_Name_Status").html(response);
        },
      });
    } else {
      $("#edit_File_Name_Status").html("");
    }
  });

  // edit showCouponsData
  const showCouponsData = (file_id, tableName, viewOnly) => {
    const url = `coupons_info.php?id=${file_id}`;
    $.getJSON(url, function (data, status) {
      data.forEach((coupon) => {
        var couponData = {
          Coupon_code: coupon.coupon_code,
          Coupon_value: coupon.coupon_value,
          Coupon_type: coupon.coupon_type,
          coupon_message: coupon.coupon_message,
        };
        edit_allCouponData.push(couponData);

        var CouponRow = " ";
        CouponRow += '<tr id="row' + coupon.coupon_id + '">';
        CouponRow += "<td>" + coupon.coupon_code + "</td>";
        CouponRow += "<td>" + coupon.coupon_value + "</td>";
        CouponRow += "<td>" + coupon.coupon_type + "</td>";
        CouponRow += "<td>" + coupon.coupon_message + "</td>";
        if (!viewOnly) {
          CouponRow += "<td>";
          CouponRow +=
            '<button id="' +
            coupon.coupon_id +
            '" class = "btn btn-danger edit_btn_remove" > ';
          CouponRow += "Delete";
          CouponRow += "</button>";
          CouponRow += "</td>";
        }
        CouponRow += "</tr>";

        tableBody = $(tableName);
        tableBody.append(CouponRow);
      });
    });
  };

  // edit coupon data row remove
  $(document).on("click", ".edit_btn_remove", function () {
    if (confirm("Are You Sure?")) {
      // alert("clicked");
      var button_id = $(this).attr("id");
      const url = `delete-coupon.php?id=${button_id}`;
      $.getJSON(url, function (data, status) {
        // alert(data);
      });
      $("#row" + button_id + "").remove();
    }
  });

  // edit Add Coupon To List
  $("#edit_addCouponToList").click(function () {
    var edit_couponData = {
      Coupon_code: $("#edit_Coupon_code").val(),
      Coupon_value: $("#edit_Coupon_value").val(),
      Coupon_type: $("#edit_Coupon_type").val(),
      coupon_message: $("#edit_coupon_message").val(),
    };
    edit_allCouponData.push(edit_couponData);

    if (
      edit_couponData.Coupon_code == "" ||
      edit_couponData.Coupon_value == "" ||
      edit_couponData.Coupon_type == "" ||
      edit_couponData.coupon_message == ""
    ) {
      alert("Add Coupon Data");
    } else {
      //alert("Clicked");
      var CouponRow = " ";
      CouponRow += '<tr id="row' + couponNumber + '">';
      CouponRow += "<td>" + edit_couponData.Coupon_code + "</td>";
      CouponRow += "<td>" + edit_couponData.Coupon_value + "</td>";
      CouponRow += "<td>" + edit_couponData.Coupon_type + "</td>";
      CouponRow += "<td>" + edit_couponData.coupon_message + "</td>";
      CouponRow += "<td>";
      CouponRow +=
        '<button id="row' +
        couponNumber +
        '" class = "btn btn-danger btn_remove" > ';
      CouponRow += "Delete";
      CouponRow += "</button>";
      CouponRow += "</td>";
      CouponRow += "</tr>";

      tableBody = $("#edit_coupon_list");
      tableBody.append(CouponRow);

      couponNumber++;
    }
  });

  // Edit Page Form Submission
  $("#editFormSubmit").click(function (event) {
    if (
      $("#edit_file_name").val() == "" ||
      $("#edit_PAYMENT_URL").val() == "" ||
      $("#edit_CORP_ID").val() == "" ||
      $("#edit_API_USERNAME").val() == "" ||
      $("#edit_API_PASSWORD").val() == "" ||
      $("#edit_AGENT_ID").val() == "" ||
      $("#edit_PAYMENT_TYPE").val() == "" ||
      $("#edit_PRODUCT_CODE").val() == "" ||
      $("#edit_PAYMENT_PROCESS").val() == "" ||
      $("#edit_SOURCE").val() == "" ||
      $("#edit_plan_name").val() == "" ||
      $("#edit_plan_type").val() == "" ||
      $("#edit_thank_you_page_url").val() == "" ||
      $("#edit_Enrollment_Fee").val() == "" ||
      $("#edit_Yearly_Payment_Fee").val() == "" ||
      $("#edit_Monthly_Payment_Fee").val() == ""
    ) {
      alert("Fillup All Data");
    } else {
      const file_id = $("#edit_file_id").val();
      const deleteurl = `delete.php?file_id=${file_id}`;
      var formData = {
        previousFileName: previousFileName,
        file_name: $("#edit_file_name").val(),

        // API Settings
        PAYMENT_URL: $("#edit_PAYMENT_URL").val(),
        CORP_ID: $("#edit_CORP_ID").val(),
        API_USERNAME: $("#edit_API_USERNAME").val(),
        API_PASSWORD: $("#edit_API_PASSWORD").val(),
        AGENT_ID: $("#edit_AGENT_ID").val(),
        PAYMENT_TYPE: $("#edit_PAYMENT_TYPE").val(),
        PRODUCT_CODE: $("#edit_PRODUCT_CODE").val(),
        PAYMENT_PROCESS: $("#edit_PAYMENT_PROCESS").val(),
        SOURCE: $("#edit_SOURCE").val(),

        // Plan Settings
        plan_name: $("#edit_plan_name").val(),
        plan_type: $("#edit_plan_type").val(),
        Yearly_payment_special: $("#edit_Yearly_payment_special").val(),
        thank_you_page_url: $("#edit_thank_you_page_url").val(),
        Enrollment_Fee: $("#edit_Enrollment_Fee").val(),

        Yearly_Payment_Fee: $("#edit_Yearly_Payment_Fee").val(),
        Monthly_Payment_Fee: $("#edit_Monthly_Payment_Fee").val(),
        CouponData: edit_allCouponData,
        file_id: file_id,
      };

      $.ajax({
        type: "POST",
        url: "update.php",
        data: formData,
        //dataType: "json",
        success: function (data) {
          location.reload();
          alert(data);
          //$('#content').html(data);
        },
      });

      event.preventDefault();
    }
  });

  //Delete File
  $(".deletefile").click(function () {
    if (confirm("Are You Sure?")) {
      const file_id = this.id;

      var formData = {
        file_id: file_id,
      };
      $.ajax({
        type: "GET",
        url: "delete.php",
        data: formData,
        //dataType: "json",
        success: function (data) {
          location.reload();
          alert(data);
        },
      });
    } else {
    }
  });
});
