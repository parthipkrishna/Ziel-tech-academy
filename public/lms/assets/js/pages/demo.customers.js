$(document).ready(function () {
    "use strict";

    if (!$.fn.DataTable) {
        console.error("DataTables library is missing or not loaded.");
        return;
    }

    $("#products-datatable").DataTable({
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'></i>",
                next: "<i class='mdi mdi-chevron-right'></i>"
            },
            info: "Showing rows _START_ to _END_ of _TOTAL_",
            lengthMenu: 'Display <select class="form-select form-select-sm ms-1 me-1">' +
                '<option value="10">10</option>' +
                '<option value="20">20</option>' +
                '<option value="-1">All</option>' +
                '</select> rows'
        },
        pageLength: 10,
        autoWidth: false, // Prevents automatic column width issues
        responsive: true, // Makes it work with different screen sizes
        order: [[0, 'desc']],// Default order disabled (so it doesn't expect a fixed column count)
        columnDefs: [
            {
                // targets: "no-sort", // Disable sorting on columns with class "no-sort"
                // orderable: false
                targets: 0,         // Column index for ID
                visible: false,     // Hides both <th> and <td>
                searchable: false   // Optional: prevent searching on this column
            }
        ],
        drawCallback: function () {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
            $("#products-datatable_length label").addClass("form-label");
            document.querySelectorAll(".dataTables_wrapper .row .col-md-6").forEach(function (el) {
                el.classList.add("col-sm-6");
                el.classList.remove("col-sm-12", "col-md-6");
            });
        }
    });
});



// $(document).ready(function () {
//     "use strict";

//     $("#products-datatable").DataTable({
//         language: {
//             paginate: {
//                 previous: "<i class='mdi mdi-chevron-left'></i>",
//                 next: "<i class='mdi mdi-chevron-right'></i>"
//             },
//             info: "Showing rows _START_ to _END_ of _TOTAL_",
//             lengthMenu: 'Display <select class="form-select form-select-sm ms-1 me-1">' +
//                 '<option value="10">10</option>' +
//                 '<option value="20">20</option>' +
//                 '<option value="-1">All</option>' +
//                 '</select> rows'
//         },
//         columnDefs: [
//             {
//                 targets: -1,
//                 className: "dt-body-right"
//             }
//         ],
//         pageLength: 10,
//         columns: [
//             {
//                 orderable: false,
//                 render: function (data, type, row, meta) {
//                     if (type === "display") {
//                         return '<div class="form-check">' +
//                             '<input type="checkbox" class="form-check-input dt-checkboxes">' +
//                             '<label class="form-check-label">&nbsp;</label>' +
//                             '</div>';
//                     }
//                     return data;
//                 },
//                 checkboxes: {
//                     selectRow: true,
//                     selectAllRender: '<div class="form-check">' +
//                         '<input type="checkbox" class="form-check-input dt-checkboxes">' +
//                         '<label class="form-check-label">&nbsp;</label>' +
//                         '</div>'
//                 }
//             },
//             { orderable: true },
//             { orderable: true },
//             { orderable: true },
//             { orderable: true },
//             { orderable: true },
//             { orderable: true },
//             { orderable: false }
//         ],
//         select: {
//             style: "multi"
//         },
//         order: [[5, "asc"]],
//         drawCallback: function () {
//             $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
//             $("#products-datatable_length label").addClass("form-label");

//             document.querySelectorAll(".dataTables_wrapper .row .col-md-6").forEach(function (el) {
//                 el.classList.add("col-sm-6");
//                 el.classList.remove("col-sm-12", "col-md-6");
//             });
//         }
//     });
// });




// $(document).ready(function(){"use strict";$("#products-datatable").DataTable({language:{paginate:{previous:"<i class='mdi mdi-chevron-left'>",next:"<i class='mdi mdi-chevron-right'>"},info:"Showing customers _START_ to _END_ of _TOTAL_",lengthMenu:'Display <select class=\'form-select form-select-sm ms-1 me-1\'><option value="10">10</option><option value="20">20</option><option value="-1">All</option></select> customers'},columnDefs:[{targets:-1,className:"dt-body-right"}],pageLength:10,columns:[{orderable:!1,render:function(e,l,a,o){return e="display"===l?'<div class="form-check"><input type="checkbox" class="form-check-input dt-checkboxes"><label class="form-check-label">&nbsp;</label></div>':e},checkboxes:{selectRow:!0,selectAllRender:'<div class="form-check"><input type="checkbox" class="form-check-input dt-checkboxes"><label class="form-check-label">&nbsp;</label></div>'}},{orderable:!0},{orderable:!0},{orderable:!0},{orderable:!0},{orderable:!0},{orderable:!0},{orderable:!1}],select:{style:"multi"},order:[[5,"asc"]],drawCallback:function(){$(".dataTables_paginate > .pagination").addClass("pagination-rounded"),$("#products-datatable_length label").addClass("form-label"),document.querySelector(".dataTables_wrapper .row").querySelectorAll(".col-md-6").forEach(function(e){e.classList.add("col-sm-6"),e.classList.remove("col-sm-12"),e.classList.remove("col-md-6")})}})});