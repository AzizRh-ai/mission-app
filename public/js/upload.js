$(document).on('change', '#media', function() {
    const MAX_FILE_SIZE = 50 * 1024 * 1024; // j'ajuste a 50Mb
    const fileName = $(this).val();
    const fileExtension = fileName.split('.').pop().toLowerCase();
    const mediaTypes = {
        'mp4': 'videos',
        'avi': 'videos',
        'mov': 'videos',
        'jpg': 'logos',
        'png': 'logos',
        'gif': 'logos',
    };
    const mediaType = mediaTypes[fileExtension] || '';
    $('#media-type').val(mediaType);
});
$(document).on('click', '#background-upload-btn', function() {
    let form = $('#media-form')[0];
    let formData = new FormData(form);
    $.ajax({
        type: 'POST',
        url: form.action,
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.type === 'videos') {
                $('.progressMedia').removeClass('d-none').text(response.success);
                setTimeout(function() {
                    $('.progressMedia').fadeOut('slow', function() {
                        $(this).addClass('d-none');
                    });
                }, 3000);
            }
        },
        error: function(xhr, status, error) {
            console.log(xhr.responseText);
        },
        xhr: function() {
            let xhr = $.ajaxSettings.xhr();
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    let percent = Math.round((e.loaded / e.total) * 100);
                    $('.progressMedia').removeClass('d-none').text('Upload en cours... ' + percent + '%');
                }
            });
            return xhr;
        }
    });
});

//Delete
$(document).on('click', '.delete-btn', function(event) {
    event.preventDefault();
    const form = $(this).closest('.delete-form');
    const url = form.attr('action');
    const id = $(this).data('id');
    const videoCount = $('[id^="video-"]').length - 1;
    const logoCount = $('[id^="logo-"]').length - 1;

    console.log(`Nombre de photos : ${logoCount}`);

    $.ajax({
        url,
        type: 'DELETE',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            const deleteMedia = $('.deleteMedia');
            deleteMedia.removeClass('d-none').text(response.success).removeAttr('style');

            setTimeout(function() {
                deleteMedia.fadeOut('slow', function() {
                    $(this).addClass('d-none');
                });
            }, 2000);

            $(`#video-${id}`).remove();

            console.log($('#video-panel').children().length);
            console.log($('#logo-panel').children().length);

            if ($('#logo-panel').children().length - 1 === 0) {
                $('#logo-empty').removeClass('d-none');
                $('#v-pills-logo').addClass('show active').removeClass('d-none');
                $('#v-pills-video').addClass('d-none');
            }

            if ($('#video-panel').children().length-1 === 0) {
                $('#video-empty').removeClass('d-none');
                $('#v-pills-video').addClass('show active').removeClass('d-none');
                $('#v-pills-logo').addClass('d-none');
            }else {

            }
        }
    });});

//Gestion de cloture pendant upload
$(window).on('beforeunload', function() {
    if (uploadInProgress) {
        return 'Upload en cours, êtes-vous sure de vouloir annuler?';
    }
});
function checkEmptyPanel(panelId, emptyDivId, uploadModalId) {
    const panel = $(`#${panelId}`);
    if (panel.children().length === 0) {
        const emptyDiv = `
            <div class="d-flex justify-content-center align-items-center h-100" id="${emptyDivId}">
                <div class="text-center">
                    <i class="fas fa-photo-video fa-5x mb-3"></i>
                    <p class="lead mb-2">Aucun média trouvé</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#${uploadModalId}">
                        Upload
                    </button>
                </div>
            </div>
        `;
        panel.append(emptyDiv);
    }
}

$('a[href="#v-pills-video"]').on('shown.bs.tab', function (e) {
    $('a[href="#v-pills-video"]').tab('show');
    $('#v-pills-video').removeClass('d-none');
    $('#v-pills-logo').addClass('d-none');
    checkEmptyPanel('video-panel', 'video-empty', 'uploadModal');
});

$('a[href="#v-pills-logo"]').on('shown.bs.tab', function (e) {
    $('a[href="#v-pills-logo"]').tab('show');
    $('#v-pills-logo').removeClass('d-none');
    $('#v-pills-video').addClass('d-none');
    checkEmptyPanel('logo-panel', 'logo-empty', 'uploadModal');
});

$('a[href="#v-pills-quizz"]').on('shown.bs.tab', function (e) {
    checkEmptyPanel('quizz-panel', 'quizz-empty', 'createModal');
});

//Gestion de l'upload de média
// Todo : Logo/Vidéo/Quizz
function uploadMedia(formSelector) {
    let form = $(formSelector)[0];
    let formData = new FormData(form);
    let uploadType = $(form).attr('data-upload-type');
    formData.append('type', uploadType === 'avatar' ? 'avatar' : $('#media-type').val());
    uploadInProgress = true;
    let fileInput = $(form).find('input[type="file"]');
    let fileType = fileInput[0].files[0].type;
    let validTypes = {
        'image/jpeg': 'jpg',
        'image/png': 'png',
        'image/gif': 'gif',
        'video/mp4': 'mp4',
        'video/avi': 'avi',
        'video/quicktime': 'mov'
    };

    if (!(fileType in validTypes)) {
        $('.error-mimetype').removeClass('d-none').text('Extension valide : jpg, png, gif, mp4, avi, mov');
        setTimeout(function() {
            $('.error-mimetype').fadeOut('slow', function() {
                $(this).addClass('d-none');
            });
        }, 5000);
        return;
    }
    $.ajax({
        type: 'POST',
        url: form.action,
        data: formData,
        xhr: function() {
            var xhr = new XMLHttpRequest();

            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    var percent = Math.round((e.loaded / e.total) * 100);
                    $('.progress-bar').attr('aria-valuenow', percent).css('width', percent + '%').text(percent + '%');
                }
            });

            return xhr;
        },
        beforeSend: function() {
            $('.progress').show();
        },
        contentType: false,
        processData: false,
        success: function(response) {

            if (response.type === 'avatar') {
                $('.profile-image').attr('src', response.url);
                $('.avatarImg').removeClass('d-none').text(response.success);
                setTimeout(function() {
                    $('.avatarImg').fadeOut('slow', function() {
                        $(this).addClass('d-none');
                    });
                }, 2000);

            }else if (response.type === 'logos') {
                let logoTab = $('#v-pills-logo');
                let logoId = response.id;
                let logoUrl = response.url;
                let logoTitle = response.title;
                if (!logoTab.hasClass('active')) {
                    // Désactiver tous les onglets actifs
                    $('#v-pills-tabContent > div.tab-pane.show.active').removeClass('show active').addClass('d-none');
                    $('.nav-link.active').removeClass('active');

                    // Activer l'onglet de la vidéo
                    logoTab.addClass('active');
                    $('#v-pills-logo-tab').addClass('active');
                    $('#v-pills-logo').addClass('show active');
                }

                let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                let logoHtml = '<div class="col-md-4 mb-4" id="video-'+logoId+'">'+
                    '<div class="card">'+
                    '<div class="card-body">'+
                    '<img src="'+logoUrl+'" class="logo-image" id="logoImg" alt="Logo image" style="max-width:100%; max-height:100%;">'+
                    '</div>'+
                    '<div class="card-footer text-muted">'+
                    '<span class="float-start"><h5 class="card-title">'+logoTitle+'</h5></span>'+
                    '<form action="/medias/'+logoId+'" method="POST" class="delete-form float-end">'+
                    '<input type="hidden" name="_token" value="'+csrfToken+'">'+
                    '<input type="hidden" name="_method" value="DELETE">'+
                    '<input type="hidden" name="id" value="'+logoId+'">'+
                    '<button type="submit" class="btn btn-danger delete-btn" data-id="'+logoId+'">Delete</button>'+
                    '</form>'+
                    '</div>'+
                    '</div>'+
                    '</div>';
                $('#logo-empty').addClass('d-none');
                $('#logo-panel').append(logoHtml);
                $('.alert-success').removeClass('d-none').text(response.success).removeAttr('style');
                setTimeout(function() {
                    $('.alert-success').fadeOut('slow', function() {
                        $(this).addClass('d-none');
                    });
                }, 2000);

            } else if (response.type === 'videos') {
                let videoTab = $('#v-pills-video');
                let videoId = response.id;
                let videoUrl = response.url;
                let videoTitle = response.title;
                if (!videoTab.hasClass('active')) {
                    $('#v-pills-tabContent > div.tab-pane.show.active').removeClass('show active').addClass('d-none');
                    $('.nav-link.active').removeClass('active');
                    videoTab.addClass('active');
                    $('#v-pills-video-tab').addClass('active');
                    $('#v-pills-video').addClass('show active');
                }

                let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                let videoHtml = '<div class="col-md-4 mb-4" id="video-'+videoId+'">'+
                    '<div class="card">'+
                    '<div class="card-body">'+
                    '<video controls width="100%" height="100%">'+
                    '<source src="'+videoUrl+'" type="video/mp4">'+
                    '</video>'+
                    '</div>'+
                    '<div class="card-footer text-muted">'+
                    '<span class="float-start"><h5 class="card-title">'+response.title+'</h5></span>'+
                    '<form action="/medias/'+videoId+'" method="POST" class="delete-form float-end">'+
                    '<input type="hidden" name="_token" value="'+csrfToken+'">'+
                    '<input type="hidden" name="_method" value="DELETE">'+
                    '<input type="hidden" name="id" value="'+response.id+'">'+
                    '<button type="submit" class="btn btn-danger delete-btn" data-id="'+response.id+'">Delete</button>'+
                    '</form>'+
                    '</div>'+
                    '</div>'+
                    '</div>';
                $('#video-empty').addClass('d-none');
                $('#video-panel').append(videoHtml);
                $('.alert-success').removeClass('d-none').text(response.success).removeAttr('style');
                setTimeout(function() {
                    $('.alert-success').fadeOut('slow', function() {
                        $(this).addClass('d-none');
                    });
                }, 2000);
            }

            $('#uploadModal').modal('hide');
            uploadInProgress = false;
            $('form')[0].reset();
        },
        error: function(xhr, status, error) {
            $('.alert-danger').removeClass('d-none').text(xhr.responseJSON.message);
            setTimeout(function() {
                $('.alert-danger').fadeOut('slow', function() {
                    $(this).addClass('d-none');
                });
            }, 4000);
        },
        complete: function() {
            $('.progress').hide();
            $('#uploadModal').modal('hide')
            uploadInProgress = false;
            $('form')[0].reset();
        }
    });
}
