<?php
include('includes/Header.php');
require_once 'config/connect_lotto_db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- datatable -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
    <title>SAC LOTTO LIST</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12"><br>
            <div><img src="img/logo/logo text-01.png" width="200" height="79"/></div>
            <h6 class="text-primary"><b>SAC LOTTO LIST</b></h6>
            <button type="button" id="backBtn" class="btn btn-danger mb-3">กลับหน้าแรก</button>

            <table id="DataTable" class="table table-striped table-hover table-bordered">
                <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>ชื่อร้าน</th>
                    <th>โทรศัพท์</th>
                    <th>จังหวัด</th>
                    <th>หมายเลข</th>
                    <th>Sale</th>
                    <th>อนุมัติ</th>
                    <th>วันที่บันทึก</th>
                    <th>รูปป้ายไวนิล</th>
                    <th>รูปเลขหลังป้าย</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $stmt = $conn->prepare("SELECT * FROM ims_lotto ORDER BY id DESC");
                $stmt->execute();
                $result = $stmt->fetchAll();
                $line_no = 0;
                foreach ($result as $rows) {
                    $line_no++;
                    ?>
                    <tr>
                        <td><?= $line_no; ?></td>
                        <td><?= htmlspecialchars($rows['lotto_name']); ?></td>
                        <td><?= htmlspecialchars($rows['lotto_phone']); ?></td>
                        <td><?= htmlspecialchars($rows['lotto_province']); ?></td>
                        <td><?= htmlspecialchars($rows['lotto_number']); ?></td>
                        <td><?= htmlspecialchars($rows['sale_name']); ?></td>
                        <td class="text-center text-<?= $rows['approve_status'] == 'Y' ? 'success' : 'secondary'; ?>">
                            <?= $rows['approve_status'] == 'Y' ? 'อนุมัติ' : 'ยังไม่อนุมัติ'; ?>
                        </td>
                        <td><?= htmlspecialchars($rows['create_date']); ?></td>

                        <td>
                            <?php
                            if (!empty($rows['lotto_file'])) {
                                foreach (explode(",", $rows['lotto_file']) as $index => $file) {
                                    $filePath = 'uploads/' . htmlspecialchars($file);
                                    echo "<a href='javascript:void(0);' class='open-popup' data-img='$filePath'>รูปที่ " . ($index + 1) . "</a><br>";
                                }
                            } else {
                                echo "ไม่มีรูป";
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if (!empty($rows['lotto_file2'])) {
                                foreach (explode(",", $rows['lotto_file2']) as $index => $file) {
                                    $filePath = 'uploads/' . htmlspecialchars($file);
                                    echo "<a href='javascript:void(0);' class='open-popup' data-img='$filePath'>รูปที่ " . ($index + 1) . "</a><br>";
                                }
                            } else {
                                echo "ไม่มีรูป";
                            }
                            ?>
                        </td>


                        <td>
                            <button class="btn btn-outline-info" onclick="openUpdateModal(<?= $rows['id']; ?>)">Update
                            </button>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Popup แสดงภาพ -->
    <div id="imagePopup" class="popup">
        <div class="popup-content">
            <span class="close-popup">✖ ปิด</span>
            <img id="popupImage" src="" alt="Preview">
        </div>
    </div>

</div>

<!-- Modal -->
<div class="modal fade" id="updateModal" name="updateModal" tabindex="-1" aria-labelledby="updateModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateForm" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id">
                    <div class="mb-3">
                        <label>ชื่อร้าน</label>
                        <input type="text" class="form-control" id="lotto_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label>โทรศัพท์</label>
                        <input type="text" class="form-control" id="lotto_phone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label>จังหวัด</label>
                        <input type="text" class="form-control" id="lotto_province" name="province" required>
                    </div>
                    <div class="mb-3">
                        <label for="lotto_number" class="control-label">หมายเลขที่เลือก
                            (000-999)</label>
                        <input type="number" class="form-control" id="lotto_number"
                               name="lotto_number"
                               min="0" max="999" required="true"
                               value=""
                               placeholder="">
                    </div>
                    <div class="mb-3">
                        <label>ชื่อ Sale</label>
                        <input type="text" class="form-control" id="sale_name" name="sale" required>
                    </div>
                    <div class="mb-3">
                        <label>สถานะอนุมัติ</label>
                        <select class="form-select" id="approve_status" name="status">
                            <option value="N">ยังไม่อนุมัติ</option>
                            <option value="Y">อนุมัติ</option>
                        </select>
                    </div>
                    <input type="hidden" id="text_lotto_file_input" value="">
                    <input type="hidden" id="text_lotto_file2_input" value="">
                    <div class="mb-3">
                        <label>อัพโหลดรูปป้ายไวนิล</label>
                        <input type="file" name="lotto_file[]" id="lotto_file_input" multiple>
                    </div>
                    <div id="lotto_file_images"></div> <!-- แสดงรูปจาก lotto_file -->
                    <div class="mb-3">
                        <label>อัพโหลดรูปเลขหลังป้าย</label>
                        <input type="file" name="lotto_file2[]" id="lotto_file2_input" multiple>
                    </div>
                    <div id="lotto_file2_images"></div> <!-- แสดงรูปจาก lotto_file2 -->
                    <input type="hidden" id="action" name="action" value="">
                    <div class="md-3">
                        <div class="form-group">
                            <button type="button" name="saveBtn" id="saveBtn" tabindex="4"
                                    class="form-control btn btn-primary">
                                            <span>
                                                <i class="fa fa-save" aria-hidden="true"></i>
                                                บันทึก
                                            </span>
                        </div>
                    </div>
                    <div class="md-3">
                        <div class="form-group">
                            <button type="button" name="closetBtn" id="closetBtn" tabindex="4"
                                    class="form-control btn btn-danger">
                                            <span>
                                                <i class="fa fa-close" aria-hidden="true"></i>
                                                ปิด
                                            </span>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* สไตล์สำหรับภาพที่แสดงเมื่อ hover */
    #imagePreview {
        display: none;
        position: fixed;
        top: 10px;
        right: 10px;
        z-index: 9999;
        border: 3px solid #333;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        max-width: 300px;
        max-height: 300px;
        background-color: #fff;
    }
</style>

<style>
    .popup {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        justify-content: center;
        align-items: center;
    }

    .popup-content {
        position: relative;
        max-width: 90%;
        max-height: 90%;
        text-align: center;
    }

    .popup-content img {
        max-width: 100%;
        max-height: 100%;
        border-radius: 10px;
    }

    .close-popup {
        position: absolute;
        top: 10px;
        right: 15px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        font-size: 18px;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    .close-popup:hover {
        background: rgba(0, 0, 0, 0.9);
    }


</style>

<!-- เพิ่มองค์ประกอบสำหรับแสดงรูปภาพ -->
<img id="imagePreview" src="" alt="Image Preview">

<script>
    $(document).ready(function () {
        $('.hover-image').hover(function (e) {
            const imgSrc = $(this).data('img');
            $('#imagePreview').attr('src', imgSrc).fadeIn();
        }, function () {
            $('#imagePreview').fadeOut();
        });

        $(document).on('mousemove', function (e) {
            $('#imagePreview').css({
                top: e.pageY + 15 + 'px',
                left: e.pageX + 15 + 'px'
            });
        });
    });
</script>


<script>
    $(document).ready(function () {
        $('#DataTable').DataTable();
        $('#backBtn').click(function () {
            window.location.href = "sac_lotto";
        });
    });
</script>

<script>
    $(document).ready(function () {
        // แสดงพรีวิวรูปภาพ
        function previewImages(inputSelector, previewContainerSelector) {
            $(inputSelector).on('change', function (e) {
                let files = e.target.files;
                let previewContainer = $(previewContainerSelector);
                previewContainer.html("");

                Array.from(files).forEach(file => {
                    let reader = new FileReader();
                    reader.onload = function (event) {
                        previewContainer.append(`
                        <div class="file-preview">
                            <img src="${event.target.result}" alt="${file.name}" width="150" height="150">
                        </div>
                    `);
                    };
                    reader.readAsDataURL(file);
                });
            });
        }

        previewImages('#lotto_file_input', '#lotto_file_images');
        previewImages('#lotto_file2_input', '#lotto_file2_images');
    });

    function openUpdateModal(id) {
        $.ajax({
            type: "POST",
            url: 'model/lotto_process.php',
            data: {action: "GET_DATA", id: id, table_name: "ims_lotto"},
            dataType: "json",
            success: function (data) {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                $('#id').val(data.id);
                $('#lotto_name').val(data.lotto_name);
                $('#lotto_phone').val(data.lotto_phone);
                $('#lotto_province').val(data.lotto_province);
                $('#lotto_number').val(data.lotto_number);
                $('#sale_name').val(data.sale_name);
                $('#approve_status').val(data.approve_status);

                $('#text_lotto_file_input').val(data.lotto_file);
                $('#text_lotto_file2_input').val(data.lotto_file2);

                displayImages('#lotto_file_images', data.lotto_file);
                displayImages('#lotto_file2_images', data.lotto_file2);

                $('#updateModal').modal('show');
            },
            error: function (xhr, status, error) {
                alert(`เกิดข้อผิดพลาด: ${status} - ${error}`);
            }
        });
    }

    function displayImages(container, files) {
        $(container).empty();
        if (files) {
            files.split(',').forEach(file => {
                let fileName = file.trim();
                if (fileName) {
                    $(container).append(`
                    <div class="file-preview">
                        <img src="uploads/${fileName}" class="img-thumbnail mb-2" style="max-width:150px;">
                        <!--button type="button" class="btn btn-danger btn-sm remove-file" data-file="${fileName}">ลบ</button-->
                    </div>
                `);
                }
            });
        }
    }

    $('#saveBtn').click(function () {
        let requiredFields = ['#lotto_name', '#lotto_phone', '#lotto_province', '#sale_name', '#lotto_number'];
        if (requiredFields.some(field => !$(field).val())) {
            alertify.error("กรุณากรอกข้อมูลให้ครบถ้วน");
            return;
        }

        let files = $('#lotto_file_input')[0].files;
        let files2 = $('#lotto_file2_input')[0].files;

/*
        if (files.length < 2) {
            alertify.error("กรุณาอัพโหลดรูปภาพ ป้ายไวนิล อย่างน้อย 2 รูป");
            return;
        }

        if (files2.length < 1) {
            alertify.error("กรุณาอัพโหลดรูปภาพ เลขหลังป้ายไวนิล อย่างน้อย 1 รูป");
            return;
        }
 */

        if (![...files, ...files2].every(file => file.type.startsWith('image/'))) {
            alertify.error("ไฟล์ที่อัพโหลดต้องเป็นไฟล์ภาพ");
            return;
        }

        let formData = new FormData();
        formData.append("action", "UPDATE");
        formData.append("id", $('#id').val());
        formData.append("table_name", "ims_lotto");
        formData.append("lotto_name", $('#lotto_name').val());
        formData.append("lotto_phone", $('#lotto_phone').val());
        formData.append("lotto_province", $('#lotto_province').val());
        formData.append("lotto_number", $('#lotto_number').val());
        formData.append("sale_name", $('#sale_name').val());
        formData.append("approve_status", $('#approve_status').val());

        Array.from(files).forEach(file => formData.append("lotto_file[]", file));
        Array.from(files2).forEach(file => formData.append("lotto_file2[]", file));

        $.ajax({
            type: "POST",
            url: 'model/lotto_process.php',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response === "0") {
                    alertify.error("ไม่สามารถบันทึกข้อมูลได้ กรุณาตรวจสอบข้อมูล");
                } else {
                    alertify.success("บันทึกสำเร็จ");
                    $('#updateModal').modal('hide');

                    // ดึงค่า ID และส่งไปใน URL
                    let id = $('#id').val();
                    window.open(`show_data_lotto_after.php?id=${id}`, '_blank');

                    // รีโหลดหน้า
                    setTimeout(function () {
                        location.reload();
                    }, 1000);

                }
            },
            error: function (xhr, status, error) {
                let errorMessage = "เกิดข้อผิดพลาด : " + error;
                if (xhr.responseText) {
                    try {
                        let response = JSON.parse(xhr.responseText);
                        errorMessage += "\n" + JSON.stringify(response, null, 2);
                    } catch (e) {
                        errorMessage += "\n" + xhr.responseText;
                    }
                }
                alertify.error(errorMessage);
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        $(".open-popup").click(function () {
            let imgSrc = $(this).attr("data-img");
            $("#popupImage").attr("src", imgSrc);
            $("#imagePopup").fadeIn();
        });

        $(".close-popup, #imagePopup").click(function () {
            $("#imagePopup").fadeOut();
        });

        $(".popup-content").click(function (event) {
            event.stopPropagation();
        });
    });

</script>

<script>
    $(document).ready(function(){
        $('#closetBtn').click(function(){
            $('#updateModal').modal('hide');
        });
    });
</script>


</body>
</html>
