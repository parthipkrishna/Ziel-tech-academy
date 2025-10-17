   //contactInfo form
   $(document).ready(function () {
        var validator = $("#ContactInfoForm").validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                address: {
                    required: true,
                    minlength: 5
                },
                phone: {
                    digits: true,
                    minlength: 10,
                    maxlength: 15
                },
                google_map_link: {
                    url: true 
                }
            },
            messages: {
                email: {
                    required: "Email is required",
                    email: "Enter a valid email address"
                },
                address: {
                    required: "Address is required",
                    minlength: "Address must be at least 5 characters long"
                },
                phone: {
                    digits: "Only numbers are allowed",
                    minlength: "Phone number must be at least 10 digits",
                    maxlength: "Phone number cannot exceed 15 digits"
                },
                google_map_link: {
                    url: "Enter a valid URL (e.g., https://example.com)"
                }
            },
            errorPlacement: function (error, element) {
                error.addClass("text-danger").insertAfter(element);
            },
            highlight: function (element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
            },
            onkeyup: function (element) {
                $(element).valid(); 
            },
            onfocusout: function (element) {
                $(element).valid(); 
            }
        });

        $("#ContactInfoForm button[type='submit']").click(function (event) {
            if (!$("#ContactInfoForm").valid()) {
                validator.focusInvalid();
                event.preventDefault();
            }
        });
    });

    //socialMedia Form
    $(document).ready(function () {
        var validator = $("#SocialMediaForm").validate({
            rules: {
                platform: {
                    required: true
                },
                url: {
                    url: true
                }
            },
            messages: {
                platform: {
                    required: "Please select a platform"
                },
                url: {
                    url: "Enter a valid URL (e.g., https://example.com)"
                }
            },
            errorPlacement: function (error, element) {
                error.addClass("text-danger").insertAfter(element);
            },
            highlight: function (element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
            },
            onkeyup: function (element) {
                $(element).valid(); 
            },
            onfocusout: function (element) {
                $(element).valid(); 
            }
        });

        $("#SocialMediaForm button[type='submit']").click(function (event) {
            if (!$("#SocialMediaForm").valid()) {
                validator.focusInvalid();
                event.preventDefault();
            }
        });
    });

    //userForm
$(document).ready(function () {
    $.validator.addMethod('imageExtension', function (value, element) {
        if (!value) return true;
        const allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif|\.webp)$/i;
        return allowedExtensions.test(value);
    }, 'Please upload a valid image (jpg, jpeg, png, gif, webp).');
    $.validator.addMethod('maxFileSize', function (value, element, maxSize) {
        if (!value) return true; // no file selected
        return element.files[0].size <= maxSize;
    }, 'File must be less than 2 MB.');
    var validator = $("#userForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 3
            },
            email: {
                required: true,
                email: true
            },
            role: { // corrected from user_role
                required: true
            },
            phone: {
                digits: true,
                minlength: 10,
                maxlength: 15
            },
            password: {
                required: true,
                minlength: 6
            },
             profile_image: {
                imageExtension: true,
                maxFileSize: 2 * 1024 * 1024
            },
        },
        messages: {
            name: {
                required: "Name is required",
                minlength: "Name must be at least 3 characters long"
            },
            email: {
                required: "Email is required",
                email: "Enter a valid email address"
            },
            role: {
                required: "Please select a user role"
            },
            phone: {
                digits: "Only numbers are allowed",
                minlength: "Phone number must be at least 10 digits",
                maxlength: "Phone number cannot exceed 15 digits"
            },
            password: {
                required: "Password is required",
                minlength: "Password must be at least 6 characters"
            },
            image: {
                    imageExtension: "Please upload a valid image (jpg, jpeg, png, gif, webp).",
                    maxFileSize: "Image size must be less than 2 MB."
                },
        },
        errorPlacement: function (error, element) {
            if (element.parent('.input-group').length) {
                error.addClass("text-danger").insertAfter(element.parent());
            } else {
                error.addClass("text-danger").insertAfter(element);
            }
        },
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
        onkeyup: function (element) {
            $(element).valid();
        },
        onfocusout: function (element) {
            $(element).valid();
        }
    });

    $("#userForm").on('submit', function (event) {
        console.log("Form submit triggered");

        if (!validator.form()) {
            validator.focusInvalid();
            event.preventDefault();
        }

        $("#userForm input, #userForm select, #userForm textarea").each(function () {
            var fieldName = $(this).attr("name");
            if (fieldName !== "name" && fieldName !== "email" && fieldName !== "password" && fieldName !== "role") {
                $(this).removeClass("is-invalid").addClass("is-valid");
            }
        });
    });
});

    //courseForm
    $(document).ready(function () {
        var validator = $("#CourseForm").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3
                },
                course_end_date: {
                    required: true,
                    date: true
                }
                
            },
            messages: {
                name: {
                    required: "Name is required",
                    minlength: "Name must be at least 3 characters long"
                },
                course_end_date: {
                    required: "Course End Date is required",
                    date: "Please enter a valid date"
                }
                
            },
            errorPlacement: function (error, element) {
                error.addClass("text-danger").insertAfter(element);
            },
            highlight: function (element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
            },
            onkeyup: function (element) {
                $(element).valid(); 
            },
            onfocusout: function (element) {
                $(element).valid(); 
            }
        });
    
        $("#CourseForm button[type='submit']").click(function (event) {
            if (!$("#CourseForm").valid()) {
                validator.focusInvalid();
                event.preventDefault();
            }
    
            $("#CourseForm input, #CourseForm textarea, #CourseForm select").each(function () {
                var fieldName = $(this).attr("name");
                if (fieldName !== "name") {  
                    $(this).removeClass("is-invalid").addClass("is-valid");
                }
            });
        });
    });
    
    //SubjectsForm
    $(document).ready(function () {
        var validator = $("#SubjectsForm").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3
                },
                total_hours: {
                    required: true,
                    digits: true,
                    min: 1
                },
                campus_id: {
                    required: true 
                },
                short_desc: {
                    required: true,
                    minlength: 5
                },
                desc: {
                    required: true,
                    minlength: 10
                }
            },
            messages: {
                name: {
                    required: "Name is required",
                    minlength: "Name must be at least 3 characters long"
                },
                total_hours: {
                    required: "Total Hours is required",
                    digits: "Only numeric values are allowed",
                    min: "Total Hours must be at least 1"
                },
                campus_id: {
                    required: "Please select a course" 
                },
                short_desc: {
                    required: "Short description is required",
                    minlength: "Short description must be at least 5 characters long"
                },
                desc: {
                    required: "Description is required",
                    minlength: "Description must be at least 10 characters long"
                }
            },
            errorPlacement: function (error, element) {
                error.addClass("text-danger").insertAfter(element);
            },
            highlight: function (element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
            }
        });
    
        $("#SubjectsForm button[type='submit']").click(function (event) {
            if (!$("#SubjectsForm").valid()) {
                validator.focusInvalid();
                event.preventDefault();
            }
            $("#SubjectsForm input, #SubjectsForm select, #SubjectsForm textarea").each(function () {
                var fieldName = $(this).attr("name");
                if (fieldName !== "name" && fieldName !== "total_hours" && fieldName !== "campus_id" && fieldName !== "short_desc" && fieldName !== "desc") {
                    $(this).removeClass("is-invalid").addClass("is-valid");
                }
            });
        });
    });

    //OfflineCourseForm
    $(document).ready(function () {
        var validator = $("#OfflineCourseForm").validate({
            rules: {
                name: { required: true, minlength: 3 },
                total_fee: { required: true, number: true, min: 0 },
                advance_fee: { required: true, number: true, min: 0 },
                monthly_fee: { required: true, number: true, min: 0 },
                base_name: { required: true },
                duration: { required: true },
                monthly_fee_duration: { required: true },
            },
            messages: {
                name: {
                    required: "Name is required",
                    minlength: "Name must be at least 3 characters long"
                },
                total_fee: { required: "Total Fee is required", number: "Enter a valid number", min: "Fee cannot be negative" },
                advance_fee: { required: "Advance Fee is required", number: "Enter a valid number", min: "Fee cannot be negative" },
                monthly_fee: { required: "Monthly Fee is required", number: "Enter a valid number", min: "Fee cannot be negative" },
                base_name: { required: "Base Name is required" },
                duration: { required: "Duration is required" },
                monthly_fee_duration: { required: "Monthly Fee Duration is required" }
            },
            errorPlacement: function (error, element) {
                error.addClass("text-danger").insertAfter(element);
            },
            highlight: function (element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
            },
            onkeyup: function (element) {
                $(element).valid();
            },
            onfocusout: function (element) {
                $(element).valid();
            }
        });

        $("#OfflineCourseForm button[type='submit']").click(function (event) {
            if (!$("#OfflineCourseForm").valid()) {
                validator.focusInvalid();
                event.preventDefault();
            }
            
            $("#OfflineCourseForm input, #OfflineCourseForm select, #OfflineCourseForm textarea").each(function () {
                var fieldName = $(this).attr("name");
                if (fieldName !== "name" && fieldName !== "total_fee" && fieldName !== "advance_fee" &&
                    fieldName !== "monthly_fee" && fieldName !== "base_name" && fieldName !== "duration" &&
                    fieldName !== "monthly_fee_duration") {
                    $(this).removeClass("is-invalid").addClass("is-valid");
                }
            });
        });
    });

    //CompanyInfoForm
    $(document).ready(function () {
        var validator = $("#CompanyInfoForm").validate({
            rules: {
                mission: { minlength: 10 }, 
                vision: { minlength: 10 }, 
                why_choose_us: { minlength: 10 }, 
                offerings: { minlength: 10 }
            },
            messages: {
                mission: { minlength: "Mission must be at least 10 characters long" },
                vision: { minlength: "Vision must be at least 10 characters long" },
                why_choose_us: { minlength: "'Why Choose Us' must be at least 10 characters long" },
                offerings: { minlength: "Offerings must be at least 10 characters long" }
            },
            errorPlacement: function (error, element) {
                error.addClass("text-danger").insertAfter(element);
            },
            highlight: function (element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
            },
            onkeyup: function (element) {
                $(element).valid();
            },
            onfocusout: function (element) {
                $(element).valid();
            }
        });

        $("#CompanyInfoForm button[type='submit']").click(function (event) {
            $("#CompanyInfoForm input, #CompanyInfoForm textarea").each(function () {
                if (!$(this).hasClass("is-invalid")) {
                    $(this).addClass("is-valid");
                }
            });
        });
    });

    //QuicklinksForm
    $(document).ready(function () {
        var validator = $("#QuicklinksForm").validate({
            rules: {
                title: {
                    required: true,
                    minlength: 3
                },
                url: {
                    url: true
                },
                order: {
                    digits: true
                }
            },
            messages: {
                title: {
                    required: "Title is required",
                    minlength: "Title must be at least 3 characters long"
                },
                url: {
                    url: "Enter a valid URL (e.g., https://example.com)"
                },
                order: {
                    digits: "Order must be a number"
                }
            },
            errorPlacement: function (error, element) {
                error.addClass("text-danger").insertAfter(element);
            },
            highlight: function (element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
            },
            onkeyup: function (element) {
                $(element).valid();
            },
            onfocusout: function (element) {
                $(element).valid();
            }
        });

        $("#QuicklinksForm button[type='submit']").click(function (event) {
            if (!$("#QuicklinksForm").valid()) {
                validator.focusInvalid();
                event.preventDefault();
            }

            $("#QuicklinksForm input").each(function () {
                var fieldName = $(this).attr("name");
                if (fieldName === "order") { // Only optional fields
                    $(this).removeClass("is-invalid").addClass("is-valid");
                }
            });
        });
    });

    //WebBannerForm
    $(document).ready(function () {
        var validator = $("#WebBannerForm").validate({
            rules: {
                title: {
                    required: true,
                    minlength: 3
                },
                type: {
                    required: true
                },
                image_url: {
                    required: true,
                    extension: "jpg|jpeg|png|gif|webp"
                }
            },
            messages: {
                title: {
                    required: "Title is required",
                    minlength: "Title must be at least 3 characters long"
                },
                type: {
                    required: "Please select a type"
                },
                image_url: {
                    required: "Image is required",
                    extension: "Only image files (JPG, PNG, GIF, WEBP) are allowed"
                }
            },
            errorPlacement: function (error, element) {
                error.addClass("text-danger").insertAfter(element);
            },
            highlight: function (element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
            },
            onkeyup: function (element) {
                $(element).valid();
            },
            onfocusout: function (element) {
                $(element).valid();
            }
        });

        $("#WebBannerForm button[type='submit']").click(function (event) {
            if (!$("#WebBannerForm").valid()) {
                validator.focusInvalid();
                event.preventDefault();
            }

            $("#WebBannerForm input, #WebBannerForm select, #WebBannerForm textarea").each(function () {
                var fieldName = $(this).attr("name");
                if (fieldName === "short_desc" || fieldName === "description") { 
                    $(this).removeClass("is-invalid").addClass("is-valid");
                }
            });
        });
    });

    //PlacementForm
    $(document).ready(function () {
        var validator = $("#PlacementForm").validate({
            rules: {
                company_name: {
                    required: true,
                    minlength: 3
                },
                website: {
                    url: true 
                }
            },
            messages: {
                company_name: {
                    required: "Company Name is required",
                    minlength: "Company Name must be at least 3 characters long"
                },
                website: {
                    url: "Enter a valid URL (e.g., https://example.com)"
                }
            },
            errorPlacement: function (error, element) {
                error.addClass("text-danger").insertAfter(element);
            },
            highlight: function (element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
            },
            onkeyup: function (element) {
                $(element).valid();
            },
            onfocusout: function (element) {
                $(element).valid();
            }
        });

        $("#PlacementForm button[type='submit']").click(function (event) {
            if (!$("#PlacementForm").valid()) {
                validator.focusInvalid();
                event.preventDefault();
            }

            $("#PlacementForm input, #PlacementForm select, #PlacementForm textarea").each(function () {
                var fieldName = $(this).attr("name");
                if (fieldName === "opportunities" || fieldName === "description") { 
                    $(this).removeClass("is-invalid").addClass("is-valid");
                }
            });
        });
    });

// StudentForm
$(document).ready(function () {
    var validator = $("#StudentForm").validate({
        rules: {
            first_name: {
                required: true,
                maxlength: 255
            },
            last_name: {
                maxlength: 255
            },
            email: {
                required: true,
                email: true,
                maxlength: 255
            },
            password: {
                required: true,
                minlength: 8
            },
            phone: {
                maxlength: 20
            },
            gender: {
                maxlength: 10
            },
            date_of_birth: {
                date: true
            },
            address: {
                maxlength: 255
            },
            city: {
                maxlength: 100
            },
            state: {
                maxlength: 100
            },
            country: {
                maxlength: 100
            },
            zip_code: {
                maxlength: 20
            },
            admission_date: {
                date: true
            },
            guardian_name: {
                maxlength: 255
            },
            guardian_contact: {
                maxlength: 20
            },
            "qc_ids[]": {
                required: true,
            },
            tutor_id: {
                required: true
            }
        },
        messages: {
            first_name: {
                required: "First Name is required",
                maxlength: "Maximum 255 characters allowed"
            },
            last_name: {
                maxlength: "Maximum 255 characters allowed"
            },
            email: {
                required: "Email is required",
                email: "Enter a valid email address",
                maxlength: "Maximum 255 characters allowed"
            },
            password: {
                required: "Password is required",
                minlength: "Password must be at least 8 characters"
            },
            phone: {
                maxlength: "Maximum 20 digits allowed"
            },
            gender: {
                maxlength: "Maximum 10 characters allowed"
            },
            date_of_birth: {
                date: "Enter a valid date"
            },
            address: {
                maxlength: "Maximum 255 characters allowed"
            },
            city: {
                maxlength: "Maximum 100 characters allowed"
            },
            state: {
                maxlength: "Maximum 100 characters allowed"
            },
            country: {
                maxlength: "Maximum 100 characters allowed"
            },
            zip_code: {
                maxlength: "Maximum 20 characters allowed"
            },
            admission_date: {
                date: "Enter a valid date"
            },
            guardian_name: {
                maxlength: "Maximum 255 characters allowed"
            },
            guardian_contact: {
                maxlength: "Maximum 20 characters allowed"
            },
            "qc_ids[]": {
                required: "Please select at least one QC"
            },
            tutor_id: {
                required: "Please select a tutor"
            }
        },
        errorPlacement: function (error, element) {
            if (element.closest('.input-group').length) {
                error.addClass("text-danger").insertAfter(element.closest('.input-group'));
            } else {
                error.addClass("text-danger").insertAfter(element);
            }
        },
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
        onkeyup: function (element) {
            $(element).valid();
        },
        onfocusout: function (element) {
            $(element).valid();
        }
    });

    $("#StudentForm button[type='submit']").click(function (event) {
        if (!$("#StudentForm").valid()) {
            validator.focusInvalid();
            event.preventDefault();
        }
    });
});

//NotificationsForm
$(document).ready(function () {
    function toggleCategorySelectors() {
        const value = $('#category_type').val();
        $('#studentSelector, #batchSelector').hide();
        $('[name="student_ids[]"], [name="batch_ids[]"]').rules('remove');

        if (value === 'student') {
            $('#studentSelector').show();
            $('[name="student_ids[]"]').rules('add', {
                required: true,
                messages: {
                    required: "Please select at least one student."
                }
            });
        } else if (value === 'batch') {
            $('#batchSelector').show();
            $('[name="batch_ids[]"]').rules('add', {
                required: true,
                messages: {
                    required: "Please select at least one batch."
                }
            });
        }
    }

    $('.select2').select2();

    const validator = $("#NotificationsForm").validate({
        ignore: [],
        rules: {
            title: {
                required: true,
                maxlength: 255
            },
            link: {
                url: true 
            },
            type: {
                required: true
            },
            category_type: {
                required: true
            },
            body: {
                required: true,
                minlength: 5
            },
            extra_info: {
                maxlength: 500
            },
            image: {
                // Optional field — no rules added
            }
        },
        messages: {
            title: {
                required: "Title is required",
                maxlength: "Maximum 255 characters allowed"
            },
            link: {
                url: "Enter a valid URL (e.g., https://example.com)"
            },
            type: {
                required: "Type is required"
            },
            category_type: {
                required: "Category Type is required"
            },
            body: {
                required: "Body is required",
                minlength: "Body must be at least 5 characters long"
            },
            extra_info: {
                maxlength: "Maximum 500 characters allowed"
            },
            image: {
                required: "Image is required" // Only applicable if required
            }
        },
        errorPlacement: function (error, element) {
            if (element.closest('.input-group').length) {
                error.addClass("text-danger").insertAfter(element.closest('.input-group'));
            } else {
                error.addClass("text-danger").insertAfter(element);
            }
        },
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
        onkeyup: function (element) {
            $(element).valid();
        },
        onfocusout: function (element) {
            $(element).valid();
        }
    });

    toggleCategorySelectors();

    $('#category_type').on('change', function () {
        toggleCategorySelectors();
    });

    $("#NotificationsForm button[type='submit']").click(function (event) {
        if (!$("#NotificationsForm").valid()) {
            validator.focusInvalid();
            event.preventDefault();
        } else {
            // Mark optional fields as green even if empty
            $('[name="extra_info"]').each(function () {
                if (!$(this).hasClass("is-valid")) {
                    $(this).addClass("is-valid").removeClass("is-invalid");
                }
            });
            $('[name="image"]').each(function () {
                if (!$(this).hasClass("is-valid")) {
                    $(this).addClass("is-valid").removeClass("is-invalid");
                }
            });
        }
    });
});

//FAQform
$(document).ready(function () {
    var validator = $("#faqForm").validate({
        rules: {
            question: {
                required: true,
                minlength: 5,
                maxlength: 1000
            },
            answer: {
                required: true,
                minlength: 5,
                maxlength: 2000
            }
        },
        messages: {
            question: {
                required: "Question is required",
                minlength: "Question must be at least 5 characters long",
                maxlength: "Maximum 1000 characters allowed"
            },
            answer: {
                required: "Answer is required",
                minlength: "Answer must be at least 5 characters long",
                maxlength: "Maximum 2000 characters allowed"
            }
        },
        errorPlacement: function (error, element) {
            if (element.closest('.input-group').length) {
                error.addClass("text-danger").insertAfter(element.closest('.input-group'));
            } else {
                error.addClass("text-danger").insertAfter(element);
            }
        },
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
        onkeyup: function (element) {
            $(element).valid();
        },
        onfocusout: function (element) {
            $(element).valid();
        }
    });

    $("#faqForm button[type='submit']").click(function (event) {
        if (!$("#faqForm").valid()) {
            validator.focusInvalid();
            event.preventDefault();
        }
    });
});

//InfluencerForm
$(document).ready(function () {
    var validator = $("#InfluencerForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 3
            },
            email: {
                email: true
            },
            phone: {
                digits: true,
                minlength: 10,
                maxlength: 15
            },
            kyc_docs: {
            },
            commission_per_user: {
                number: true,
                min: 0
            },
            image: {
                extension: "jpg|jpeg|png|gif|webp"
            }
        },
        messages: {
            name: {
                required: "Name is required",
                minlength: "Name must be at least 3 characters"
            },
            email: {
                email: "Enter a valid email"
            },
            phone: {
                digits: "Only numbers are allowed",
                minlength: "Phone number must be at least 10 digits",
                maxlength: "Phone number cannot exceed 15 digits"
            },
            kyc_docs: {
            },
            commission_per_user: {
                number: "Enter a valid number",
                min: "Commission cannot be negative"
            },
            image: {
                extension: "Only JPG, JPEG, PNG, GIF, or WEBP images are allowed"
            }
        },
        errorPlacement: function (error, element) {
            error.addClass("text-danger").insertAfter(element);
        },
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
        onkeyup: function (element) {
            $(element).valid(); // Validate on keyup
        },
        onfocusout: function (element) {
            $(element).valid(); // Validate on focusout
        }
    });

    // Trigger validation when the form is submitted
    $("#InfluencerForm").submit(function (event) {
        if (!$("#InfluencerForm").valid()) {
            event.preventDefault(); // Prevent form submission if invalid
            validator.focusInvalid(); // Focus on the first invalid field
        }
    });

    // Optional: You can also use the "click" handler if needed
    $("#InfluencerForm button[type='submit']").click(function (event) {
        if (!$("#InfluencerForm").valid()) {
            validator.focusInvalid(); // Focus on the first invalid field
            event.preventDefault(); // Prevent form submission if invalid
        }
    });
});

$(document).ready(function () {
    var validator = $("#EnrollmentForm").validate({
        rules: {
            first_name: {
                required: true,
                minlength: 3
            },
            email: {
                required: true,
                email: true
            },
            course_id: {
                required: true
            },
            phone: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 15
            },
          
        },
        messages: {
            first_name: {
                required: "First name is required",
                minlength: "First name must be at least 3 characters long"
            },
            email: {
                required: "Email is required",
                email: "Enter a valid email address"
            },
            course_id: {
                required: "Please select a course"
            },
            phone: {
                required: "Phone number is required",
                digits: "Only numbers are allowed",
                minlength: "Phone number must be at least 10 digits",
                maxlength: "Phone number cannot exceed 15 digits"
            },
            
        },
        errorPlacement: function (error, element) {
            if (element.parent('.input-group').length) {
                error.addClass("text-danger").insertAfter(element.parent());
            } else {
                error.addClass("text-danger").insertAfter(element);
            }
        },
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
        onkeyup: function (element) {
            $(element).valid();
        },
        onfocusout: function (element) {
            $(element).valid();
        }
    });

    $("#EnrollmentForm").on('submit', function (event) {
        console.log("EnrollmentForm submit triggered");

        if (!validator.form()) {
            validator.focusInvalid();
            event.preventDefault();
        }

        // Mark other fields as valid if not part of main rules
        $("#EnrollmentForm input, #EnrollmentForm select, #EnrollmentForm textarea").each(function () {
            var fieldName = $(this).attr("name");
            if (fieldName !== "first_name" && fieldName !== "email" && fieldName !== "course_id" && fieldName !== "phone" && fieldName !== "status") {
                $(this).removeClass("is-invalid").addClass("is-valid");
            }
        });
    });
});

$(document).ready(function () {
    var validator = $("#toolKitForm").validate({
        ignore: [], // include hidden inputs also
        rules: {
            name: {
                required: true,
                minlength: 3,
                maxlength: 255
            },
            course_id: {
                required: true
            },
            price: {
                number: true,
                min: 0
            },
            offer_price: {
                number: true,
                min: 0,
                max: function () {
                    return parseFloat($("#price").val()) || 999999;
                }
            },
            description: {
                maxlength: 2000
            },
            short_description: {
                maxlength: 1000
            },
            "media[]": {
                extension: "jpg|jpeg|png|gif|svg|webp",
                filesize: 2097152 // 2MB (custom rule below)
            }
        },
        messages: {
            name: {
                required: "Tool kit name is required",
                minlength: "Name must be at least 3 characters",
                maxlength: "Maximum 255 characters allowed"
            },
            course_id: {
                required: "Please select a course"
            },
            price: {
                number: "Enter a valid price",
                min: "Price cannot be negative"
            },
            offer_price: {
                number: "Enter a valid offer price",
                min: "Offer price cannot be negative",
                max: "Offer price cannot be higher than Price"
            },
            description: {
                maxlength: "Maximum 2000 characters allowed"
            },
            short_description: {
                maxlength: "Maximum 1000 characters allowed"
            },
            "media[]": {
                extension: "Only jpg, jpeg, png, gif, svg, webp files are allowed",
                filesize: "Each file must be less than 2 MB"
            }
        },
        errorPlacement: function (error, element) {
            if (element.closest('.input-group').length) {
                error.addClass("text-danger").insertAfter(element.closest('.input-group'));
            } else {
                error.addClass("text-danger").insertAfter(element);
            }
        },
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
        onkeyup: function (element) {
            $(element).valid();
        },
        onfocusout: function (element) {
            $(element).valid();
        }
    });

    // custom filesize validation rule
    $.validator.addMethod("filesize", function (value, element, param) {
        var isValid = true;
        $.each(element.files, function (i, file) {
            if (file.size > param) {
                isValid = false;
            }
        });
        return isValid;
    });

    $("#toolKitForm button[type='submit']").click(function (event) {
        if (!$("#toolKitForm").valid()) {
            validator.focusInvalid();
            event.preventDefault();
        }
    });
});

