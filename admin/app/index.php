<?php
    session_start();
    if(!isset($_SESSION["loggedin"]))
    {
        ?>
<script type="text/javascript">
alert("Please Login First");
window.location = "../index.php";
</script>
<?php
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Checkout Page Manager</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="appstyle/style.css">
    <!-- <script src="js/script.js"></script> -->
</head>

<body>
    <div class="container-xl">
        <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-2">
                            <a class="btn btn-danger" href="..\logout.php">Logout</a>
                        </div>
                        <div class="col-sm-6">
                            <h2>Manage <b>Checkout Page</b></h2>
                        </div>
                        <div class="col-sm-4">
                            <a href="#addModal" class="btn btn-success" data-toggle="modal"><i
                                    class="material-icons">&#xE147;</i> <span>Add New Page</span></a>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-hover" style=" width: 100%;">
                    <thead>
                        <tr>
                            <th class="w-25">Page Name</th>
                            <th class="w-50">URL</th>
                            <th class="w-25">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="position-relative">
                        <?php
                                    require_once "../config.php";
                                    $sql = "SELECT * from checkout_file";
                                    $result = mysqli_query($conn, $sql) or die (mysqli_error($conn));
                                    while ($row = mysqli_fetch_assoc($result)) {

                                        $current_url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ;
                                        $new_url = str_replace('admin/app/index.php', $row['file_name'], $current_url);

                                    echo '<tr  class="table w-100" id="'.$row['file_id'].'">';
                                        echo '<td class="w-25">'.$row['file_name'].'.php </td>';
                                        echo '<td class="w-50">'.$new_url.'.php</td>';
                                        echo '<td class="">';
                                            echo '<a id="'. $row['file_id'].'" href="#viewModal" class="viewFile" data-toggle="modal" data-toggle="tooltip" title="View"><i class="material-icons">&#xE417;</i></a>
                                            <a id="'. $row['file_id'].'" href="#editModal" class="editFile" data-toggle="modal" data-toggle="tooltip" title="Edit"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                                            <a id="'. $row['file_id'].'" href="" class="deletefile" data-toggle="modal" data-toggle="tooltip" title="Delete"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>';
                                        echo '</td>';
                                    echo '</tr>';
                                    }
                                ?>
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Add File Modal  -->
        <div id="addModal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
            aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div id="createForm">
                        <div class="modal-header">
                            <h4 class="modal-title">Generate File</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">

                            <!-- File Name -->
                            <div class="m-2">
                                <div class="row">
                                    <div class="form-group col-8">
                                        <label>
                                            <h5>File Name</h5>
                                        </label>
                                        <input type="text" name="file_name" id="file_name" class="form-control"
                                            required>
                                    </div>

                                    <div class="col-4 ">
                                        <label for="File_Name_Status">File Name Status</label>
                                        <h6 id="File_Name_Status">
                                        </h6>
                                    </div>
                                </div>
                            </div>

                            <!-- API Settings -->
                            <div class="m-2">
                                <h5>API Settings</h5>
                                <div class="form-group">
                                    <div class="row d-flex align-items-center">
                                        <div class="col-2 me-0 pe-0">
                                            <label>PAYMENT URL</label>
                                        </div>
                                        <div class="col-10 ms-0 ps-0">
                                            <input type="text" name="PAYMENT_URL" id="PAYMENT_URL" class="form-control"
                                                required>
                                        </div>
                                    </div>

                                    <div class="row d-flex align-items-center mt-1">
                                        <div class="form-group col-4">
                                            <label>CORP ID</label>
                                            <input type="text" name="CORP_ID" id="CORP_ID" class="form-control"
                                                required>
                                        </div>
                                        <div class="form-group col-4">
                                            <label>API USERNAME</label>
                                            <input type="text" name="API_USERNAME" id="API_USERNAME"
                                                class="form-control" required>
                                        </div>
                                        <div class="form-group col-4">
                                            <label>API PASSWORD</label>
                                            <input type="text" name="API_PASSWORD" id="API_PASSWORD"
                                                class="form-control" required>
                                        </div>
                                    </div>


                                    <div class="row d-flex align-items-center mt-1">
                                        <div class="form-group col-4">
                                            <label>AGENT ID</label>
                                            <input type="text" name="AGENT_ID" id="AGENT_ID" class="form-control"
                                                required>
                                        </div>
                                        <div class="form-group col-4">
                                            <label>PAYMENT TYPE</label>
                                            <input type="text" name="PAYMENT_TYPE" id="PAYMENT_TYPE"
                                                class="form-control" required>
                                        </div>
                                        <div class="form-group col-4">
                                            <label>PRODUCT_CODE</label>
                                            <input type="text" name="PRODUCT_CODE" id="PRODUCT_CODE"
                                                class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row d-flex align-items-center mt-1">
                                        <div class="form-group col-6">
                                            <label>PAYMENT PROCESS</label>
                                            <input type="text" name="PAYMENT_PROCESS" id="PAYMENT_PROCESS"
                                                class="form-control" required>
                                        </div>
                                        <div class="form-group col-6">
                                            <label>SOURCE</label>
                                            <input type="text" name="SOURCE" id="SOURCE" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Plan Settings -->
                            <div class="m-2">
                                <h5>Plan Settings</h5>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Plan name</label>
                                            <input type="text" name="plan_name" id="plan_name" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Plan type</label>
                                            <select class="form-control" name="plan_type" type="radio" id="plan_type"
                                                required>
                                                <option value="Family">Family</option>
                                                <option value="Individual">Individual</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Yearly Payment Special</label>
                                            <input class="form-control" name="Yearly_payment_special"
                                                id="Yearly_payment_special" cols="63" rows="2"></input>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Thank You Page URL</label>
                                            <input class="form-control" name="thank_you_page_url"
                                                id="thank_you_page_url" cols="63" rows="2"></input>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-4">
                                        <label>Enrollment Fee</label>
                                        <input type="text" name="Enrollment_Fee" id="Enrollment_Fee"
                                            class="form-control" required>
                                    </div>
                                    <div class="form-group col-4">
                                        <label>Yearly Payment Fee</label>
                                        <input type="text" name="Yearly_Payment_Fee" id="Yearly_Payment_Fee"
                                            class="form-control" required>
                                    </div>
                                    <div class="form-group col-4">
                                        <label>Monthly Payment Fee</label>
                                        <input type="text" name="Monthly_Payment_Fee" id="Monthly_Payment_Fee"
                                            class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Coupons -->
                            <div class="m-2">
                                <h5>Coupons</h5>
                                <div class="row">
                                    <div class="col-2 form-group">
                                        <label>Coupon Code</label>
                                        <input class="form-control" type="text" name="Coupon_code[]" id="Coupon_code">
                                    </div>

                                    <div class="col-2 form-group">
                                        <label>Coupon Value</label>
                                        <input class="form-control" type="text" name="Coupon_value[]" id="Coupon_value">
                                    </div>

                                    <div class="col-2 form-group">
                                        <label>Coupon Type</label>
                                        <select Type="radio" class="form-control" name="Coupon_type[]" id="Coupon_type">
                                            <option value="Flat">Flat</option>
                                            <option value="Percentage">Percentage</option>
                                        </select>
                                        <!-- <input class="form-control" type="text" name="Coupon_type[]" id="Coupon_type"> -->
                                    </div>

                                    <div class="col-5 form-group">
                                        <label>Coupon Message</label>
                                        <textarea name="coupon_message[]" class="form-control" id="coupon_message"
                                            rows="2"></textarea>
                                    </div>
                                    <div class="col-1">
                                        <button id="addCouponToList" class="btn btn-sm btn-primary">
                                            Add
                                        </button>
                                    </div>
                                </div>
                                <div class="row" id="couponRow"></div>
                            </div>

                            <!-- Coupons Table-->
                            <div class="mt-4">
                                <table id="add_table" class="table table-hover border">
                                    <thead>
                                        <tr>
                                            <th scope="col">Coupon</th>
                                            <th scope="col">Value</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Message</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="coupon_list">

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                            <input id="CreateFormSubmit" type="submit" class="btn btn-success" value="Add">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit File Modal  -->
        <div id="editModal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
            aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div id="editFileForm">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit File</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="edit_file_id" id="edit_file_id" value="">

                            <!-- File Name -->
                            <div class="m-2">
                                <div class="row">
                                    <div class="form-group col-8">
                                        <label>
                                            <h5>File Name</h5>
                                        </label>
                                        <input type="text" name="edit_file_name" id="edit_file_name"
                                            class="form-control" required>
                                    </div>

                                    <div class="col-4 ">
                                        <label for="edit_File_Name_Status">File Name Status</label>
                                        <h6 id="edit_File_Name_Status">
                                        </h6>
                                    </div>
                                </div>
                            </div>

                            <!-- API Settings -->
                            <div class="m-2">
                                <h5>API Settings</h5>
                                <div class="form-group">
                                    <div class="row d-flex align-items-center">
                                        <div class="col-2 me-0 pe-0">
                                            <label>PAYMENT URL</label>
                                        </div>
                                        <div class="col-10 ms-0 ps-0">
                                            <input type="text" name="edit_PAYMENT_URL" id="edit_PAYMENT_URL"
                                                class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row d-flex align-items-center mt-1">
                                        <div class="form-group col-4">
                                            <label>CORP ID</label>
                                            <input type="text" name="edit_CORP_ID" id="edit_CORP_ID"
                                                class="form-control" required>
                                        </div>
                                        <div class="form-group col-4">
                                            <label>API USERNAME</label>
                                            <input type="text" name="edit_API_USERNAME" id="edit_API_USERNAME"
                                                class="form-control" required>
                                        </div>
                                        <div class="form-group col-4">
                                            <label>API PASSWORD</label>
                                            <input type="text" name="edit_API_PASSWORD" id="edit_API_PASSWORD"
                                                class="form-control" required>
                                        </div>
                                    </div>


                                    <div class="row d-flex align-items-center mt-1">
                                        <div class="form-group col-4">
                                            <label>AGENT ID</label>
                                            <input type="text" name="edit_AGENT_ID" id="edit_AGENT_ID"
                                                class="form-control" required>
                                        </div>
                                        <div class="form-group col-4">
                                            <label>PAYMENT TYPE</label>
                                            <input type="text" name="edit_PAYMENT_TYPE" id="edit_PAYMENT_TYPE"
                                                class="form-control" required>
                                        </div>
                                        <div class="form-group col-4">
                                            <label>PRODUCT_CODE</label>
                                            <input type="text" name="edit_PRODUCT_CODE" id="edit_PRODUCT_CODE"
                                                class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row d-flex align-items-center mt-1">
                                        <div class="form-group col-6">
                                            <label>PAYMENT PROCESS</label>
                                            <input type="text" name="edit_PAYMENT_PROCESS" id="edit_PAYMENT_PROCESS"
                                                class="form-control" required>
                                        </div>
                                        <div class="form-group col-6">
                                            <label>SOURCE</label>
                                            <input type="text" name="edit_SOURCE" id="edit_SOURCE" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Plan Settings -->
                            <div class="m-2">
                                <h5>Plan Settings</h5>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Plan name</label>
                                            <input type="text" name="edit_plan_name" id="edit_plan_name"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Plan type</label>
                                            <select class="form-control" name="edit_plan_type" type="radio"
                                                id="edit_plan_type" required>
                                                <option value="Family">Family</option>
                                                <option value="Individual">Individual</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Yearly Payment Special</label>
                                            <input class="form-control" name="edit_Yearly_payment_special"
                                                id="edit_Yearly_payment_special" cols="63" rows="2"></input>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Thank You Page URL</label>
                                            <input class="form-control" name="edit_thank_you_page_url"
                                                id="edit_thank_you_page_url" cols="63" rows="2"></input>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-4">
                                        <label>Enrollment Fee</label>
                                        <input type="text" name="edit_Enrollment_Fee" id="edit_Enrollment_Fee"
                                            class="form-control" required>
                                    </div>
                                    <div class="form-group col-4">
                                        <label>Yearly Payment Fee</label>
                                        <input type="text" name="edit_Yearly_Payment_Fee" id="edit_Yearly_Payment_Fee"
                                            class="form-control" required>
                                    </div>
                                    <div class="form-group col-4">
                                        <label>Monthly Payment Fee</label>
                                        <input type="text" name="edit_Monthly_Payment_Fee" id="edit_Monthly_Payment_Fee"
                                            class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Coupons -->
                            <div class="m-2">
                                <h5>Coupons</h5>
                                <div class="row">
                                    <div class="col-2 form-group">
                                        <label>Coupon Code</label>
                                        <input class="form-control" type="text" name="edit_Coupon_code[]"
                                            id="edit_Coupon_code">
                                    </div>

                                    <div class="col-2 form-group">
                                        <label>Coupon Value</label>
                                        <input class="form-control" type="text" name="edit_Coupon_value[]"
                                            id="edit_Coupon_value">
                                    </div>

                                    <div class="col-2 form-group">
                                        <label>Coupon Type</label>
                                        <select Type="radio" class="form-control" name="edit_Coupon_type[]"
                                            id="edit_Coupon_type">
                                            <option value="Flat">Flat</option>
                                            <option value="Percentage">Percentage</option>
                                        </select>
                                        <!-- <input class="form-control" type="text" name="Coupon_type[]" id="Coupon_type"> -->
                                    </div>

                                    <div class="col-5 form-group">
                                        <label>Coupon Message</label>
                                        <textarea name="edit_coupon_message[]" class="form-control"
                                            id="edit_coupon_message" rows="2"></textarea>
                                    </div>
                                    <div class="col-1">
                                        <button id="edit_addCouponToList" class="btn btn-sm btn-primary">
                                            Add
                                        </button>
                                    </div>
                                </div>
                                <div class="row" id="edit_couponRow"></div>
                            </div>

                            <!-- Coupons Table-->
                            <div class="mt-4">
                                <table id="edit_table" class="table table-hover border">
                                    <thead>
                                        <tr>
                                            <th scope="col">Coupon</th>
                                            <th scope="col">Value</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Message</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="edit_coupon_list">

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                            <input id="editFormSubmit" type="submit" class="btn btn-success" value="Submit">
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- View File Modal  -->
        <div id="viewModal" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Checkout File</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body p-4">
                    <!-- General Info -->
                        <div class="m-1 fs-5">
                            <h5>General Info</h5>
                            <div class="row d-flex align-items-center justify-content-between">
                                <div class="col-4">
                                    Page URL:
                                </div>
                                <div class="col-8">
                                    <p class="" id="view_PAGE_URL"></p>
                                </div>
                            </div>
                            <div class="row d-flex align-items-center justify-content-between">
                                <div class="col-4">
                                    File Name
                                </div>
                                <div class="col-8">
                                    <p class="" id="view_File_Name"></p>
                                </div>
                            </div>
                        </div>


                        <div class="mt-4 fs-5">
                            <h5>API Settings</h5>
                            
                            <div class="row mt-1">
                                <div class="col-6">
                                    PAYMENT URL
                                </div>
                                <div id="view_PAYMENT_URL" class="col-6">
                                </div>
                            </div>


                            <div class="row mt-2 d-flex align-items-center justify-content-between">
                                <div class="row col-6 d-flex align-items-center justify-content-between">
                                    <div class="col-6">
                                        CORP ID
                                    </div>
                                    <div class="col-6" id="view_CORP_ID">
                                    </div>
                                </div>
                                <div class="row col-6 d-flex align-items-center justify-content-between">
                                    <div class="col-6">
                                        API USERNAME
                                    </div>
                                    <div class="col-6" id="view_API_USERNAME">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2 d-flex align-items-center justify-content-between">
                                <div class="row col-6 d-flex align-items-center justify-content-between">
                                    <div class="col-6">
                                        API PASSWORD
                                    </div>
                                    <div class="col-6" id="view_API_PASSWORD">
                                    </div>
                                </div>
                                <div class="row col-6">
                                    <div class="col-6">
                                        AGENT ID
                                    </div>
                                    <div class="col-6" id="view_AGENT_ID">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2 d-flex align-items-center justify-content-between">
                                <div class="row col-6 d-flex align-items-center justify-content-between">
                                    <div class="col-6">
                                        PAYMENT TYPE
                                    </div>
                                    <div class="col-6" id="view_PAYMENT_TYPE">
                                    </div>
                                </div>
                                <div class="row col-6">
                                    <div class="col-6">
                                        PRODUCT CODE
                                    </div>
                                    <div class="col-6" id="view_PRODUCT_CODE">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2 d-flex align-items-center justify-content-between">
                                <div class="row col-6 d-flex align-items-center justify-content-between">
                                    <div class="col-6">
                                        PAYMENT PROCESS
                                    </div>
                                    <div class="col-6" id="view_PAYMENT_PROCESS">
                                    </div>
                                </div>
                                <div class="row col-6">
                                    <div class="col-6">
                                        SOURCE
                                    </div>
                                    <div class="col-6" id="view_SOURCE">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Plan Settings -->
                        <div class="mt-4 fs-5">
                            <h5>Plan Settings</h5>

                            <div class="row mt-2 d-flex align-items-center justify-content-between">
                                <div class="row col-6 d-flex align-items-center justify-content-between">
                                    <div class="col-6">
                                        Plan name
                                    </div>
                                    <div class="col-6" id="view_plan_name">
                                    </div>
                                </div>
                                <div class="row col-6">
                                    <div class="col-6">
                                        Plan type
                                    </div>
                                    <div class="col-6" id="view_plan_type">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2 d-flex align-items-center justify-content-between">
                                <div class="row col-6 d-flex align-items-center justify-content-between">
                                    <div class="col-6">
                                        Yearly Payment Special
                                    </div>
                                    <div class="col-6" id="view_Yearly_payment_special">
                                    </div>
                                </div>
                                <div class="row col-6">
                                    <div class="col-6">
                                        Thank You Page URL
                                    </div>
                                    <div class="col-6" id="view_thank_you_page_url">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2 d-flex align-items-center justify-content-between">
                                <div class="row col-6 d-flex align-items-center justify-content-between">
                                    <div class="col-6">
                                        Enrollment Fee
                                    </div>
                                    <div class="col-6" id="view_Enrollment_Fee">
                                    </div>
                                </div>
                                <div class="row col-6">
                                    <div class="col-6">
                                        Yearly Payment Fee
                                    </div>
                                    <div class="col-6" id="view_Yearly_Payment_Fee">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2 d-flex align-items-center justify-content-between">
                                
                                <div class="row col-6">
                                    <div class="col-6">
                                        Monthly Payment Fee
                                    </div>
                                    <div class="col-6" id="view_Monthly_Payment_Fee">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- API Settings -->
                        <div class="mt-4 fs-5">
                            <!-- Coupons -->
                            <h5>Coupons</h5>
                            <!-- Coupons Table-->
                            <div class="mt-4">
                                <table id="add_table" class="table table-hover border">
                                    <thead>
                                        <tr>
                                            <th scope="col">Coupon</th>
                                            <th scope="col">Value</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Message</th>
                                        </tr>
                                    </thead>
                                    <tbody id="view_coupon_list">

                                    </tbody>
                                </table>
                            </div>

                        </div>





                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-warning" data-dismiss="modal" value="Close">
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Modal  -->
    <!-- <div id="editModal2" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editFileForm">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit File</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="edit_file_id" id="edit_file_id" value="">
                        <div class="row">
                            <div class="form-group col-8">
                                <label>File Name</label>
                                <input type="text" name="file_name" id="edit_file_name" class="form-control" required>
                            </div>
                            <div class="col-4 ">
                                <h6 id="edit_File_Name_Status">
                                </h6>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Plan name</label>
                            <input type="text" name="plan_name" id="edit_plan_name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Plan type</label>
                            <select class="form-control" name="edit_plan_type" id="edit_plan_type" type="radio"
                                id="plan_type">
                                <option value="Family">Family</option>
                                <option value="Individual">Individual</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="form-group col-6">
                                <label>Yearly Payment Fee</label>
                                <input type="text" name="Yearly_Payment_Fee" id="edit_Yearly_Payment_Fee"
                                    class="form-control" required>
                            </div>
                            <div class="form-group col-6">
                                <label>Monthly Payment Fee</label>
                                <input type="test" name="Monthly_Payment_Fee" id="edit_Monthly_Payment_Fee"
                                    class="form-control" required>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                                    <label>Yearly Payment Special</label>
                                    <textarea name="Yearly_payment_special" id="edit_Yearly_payment_special" cols="52" rows="2"></textarea>
                                </div> 

                                <div class="form-group">
                                    <label>Source</label>
                                    <textarea name="edit_source" id="edit_source" ></textarea>
                                </div> 

                        <div class="form-group">
                            <label>Yearly Payment Special</label>
                            <input class="form-control" name="Yearly_payment_special"
                                id="edit_Yearly_payment_special"></input>
                        </div>

                        <div class="form-group">
                            <label>Source</label>
                            <input class="form-control" type="text" name="edit_source" id="edit_source"></input>
                        </div>


                        <div class="row">
                            <div class="form-group col-6">
                                <label>Enrollment Fee</label>
                                <input type="text" name="edit_Enrollment_Fee" id="edit_Enrollment_Fee" class="form-control"
                                    required>
                            </div>
                            <div class="col-6 form-group">
                                <label>Promo Type</label>
                                <select class="form-control" name="Promo_type" type="radio" id="edit_Promo_type">
                                    <option value="Percentage">%-Percentage</option>
                                    <option value="Flat Amount">$-Flat Amount</option>
                                </select>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-6 form-group">
                                <label>Promo Factor</label>
                                <input class="form-control" type="text" name="Promo_Factor" id="edit_Promo_Factor">
                            </div>
                            <div class="col-6 form-group">
                                <label>Priority</label>
                                <input class="form-control" type="number" name="Priority" id="edit_Priority">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <input type="submit" class="btn btn-success" value="Add">
                    </div>
                </form>
            </div>
        </div>
    </div> -->
    <!-- Delete Modal  -->
    <div id="deleteModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deleteFileForm">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete File</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete these File?</p>
                        <p class="text-warning"><small>This action cannot be undone.</small></p>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <input type="submit" class="btn btn-danger" value="Delete">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="js/scripts.js"></script>
</body>

</html>