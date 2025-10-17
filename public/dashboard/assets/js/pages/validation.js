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
                user_role: {
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
                    extension: "jpg|jpeg|png|gif|webp"
                }
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
                user_role: {
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
                profile_image: {
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
    
        $("#userForm button[type='submit']").click(function (event) {
            if (!$("#userForm").valid()) {
                validator.focusInvalid();
                event.preventDefault();
            }
    
            $("#userForm input, #userForm select, #userForm textarea").each(function () {
                var fieldName = $(this).attr("name");
                if (fieldName !== "name" && fieldName !== "email" && fieldName !== "password" && fieldName !== "user_role") {
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
                }
            },
            messages: {
                name: {
                    required: "Name is required",
                    minlength: "Name must be at least 3 characters long"
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



        
                

