(function ($) {
    var i18n = $('body').data('i18n') || {};
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    $.ajaxSetup({ headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') } });

    function tr(key, params) {
        var text = i18n[key] || key;
        $.each(params || {}, function (name, value) {
            text = text.replace('{' + name + '}', value);
        });
        return text;
    }

    function post(url, data) {
        return $.ajax({ type: 'POST', url: url, data: data, dataType: 'json' });
    }

    function errorText(xhr) {
        return (xhr.responseJSON && xhr.responseJSON.error) || tr('generic');
    }

    function showError($box, message) {
        $box.text(message).prop('hidden', false);
    }

    var theme = document.documentElement.getAttribute('data-theme');
    var $themeBtn = $('#theme-toggle').attr('aria-pressed', theme === 'dark');
    $themeBtn.on('click', function () {
        var next = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', next);
        $themeBtn.attr('aria-pressed', next === 'dark');
        try { localStorage.setItem('theme', next); } catch (e) {}
    });

    $('#logout').on('click', function () {
        post('/_ajax/logout.php').always(function () { window.location.href = '/'; });
    });

    var $modal = $('#auth-modal');
    $('#open-auth').on('click', function () { $modal.modal('show'); });
    var query = new URLSearchParams(window.location.search);
    if (query.get('login') === '1' && $('body').data('auth') === 0) {
        $modal.modal('show');
    }

    $modal.on('shown.bs.modal', function () {
        $modal.find('form:visible input:first').trigger('focus');
    });

    $('.auth-switch').on('click', function () {
        var $forms = $('.auth-form');
        $forms.prop('hidden', function (i, hidden) { return !hidden; });
        $forms.filter(':visible').find('input:first').trigger('focus');
    });

    $('.auth-form').on('submit', function (event) {
        event.preventDefault();
        var $form = $(this);
        var $error = $form.find('.form-error').prop('hidden', true);
        post($form.data('endpoint'), $form.serialize()).done(function () {
            var next = query.get('next');
            window.location.href = next && /^\/(?!\/)[\w\-.?=&%\/]*$/.test(next) ? next : window.location.pathname;
        }).fail(function (xhr) { showError($error, errorText(xhr)); });
    });

    var $countdown = $('#countdown');
    if ($countdown.length) {
        var target = new Date($countdown.data('target')).getTime();
        var pad = function (n) { return String(n).padStart(2, '0'); };
        var tick = function () {
            var s = Math.max(0, Math.floor((target - Date.now()) / 1000));
            $countdown.text(
                Math.floor(s / 86400) + tr('days') + ' ' + pad(Math.floor(s % 86400 / 3600)) + tr('hours') + ' ' +
                pad(Math.floor(s % 3600 / 60)) + tr('minutes') + ' ' + pad(s % 60) + tr('seconds')
            );
        };
        tick();
        setInterval(tick, 1000);
    }

    var $picker = $('#picker');
    if ($picker.length) {
        var selected = [];
        var $buttons = $picker.find('.ball-btn');
        var $slots = $('#slots .ball');
        var $status = $('#pick-status');
        var $err = $('#pick-error');

        var render = function () {
            var sorted = selected.slice().sort(function (a, b) { return a - b; });
            $buttons.each(function () {
                $(this).attr('aria-pressed', selected.indexOf($(this).data('n')) !== -1);
            });
            $slots.each(function (i) {
                $(this).text(sorted[i] || '').toggleClass('ball-drawn', i < sorted.length).toggleClass('ball-empty', i >= sorted.length);
            });
            $status.text(tr('count', { n: selected.length }));
            $('#pick-add').prop('disabled', selected.length !== 6);
        };

        $buttons.on('click', function () {
            var n = $(this).data('n');
            var at = selected.indexOf(n);
            $err.prop('hidden', true);
            if (at !== -1) {
                selected.splice(at, 1);
            } else if (selected.length < 6) {
                selected.push(n);
            } else {
                showError($err, tr('limit'));
            }
            render();
        });

        $('#pick-clear').on('click', function () { selected = []; $err.prop('hidden', true); render(); });

        $('#pick-random').on('click', function () {
            var pool = [];
            for (var i = 1; i <= 45; i++) { pool.push(i); }
            var buf = new Uint32Array(pool.length);
            crypto.getRandomValues(buf);
            pool = pool.map(function (n, i) { return [buf[i], n]; }).sort(function (a, b) { return a[0] - b[0]; });
            selected = pool.slice(0, 6).map(function (p) { return p[1]; });
            $err.prop('hidden', true);
            render();
        });

        $('#pick-add').on('click', function () {
            var $btn = $(this).prop('disabled', true);
            post('/_ajax/cart-add.php', { nums: selected }).done(function (res) {
                $('#cart-count').text(res.count);
                selected = [];
                render();
                $status.text(tr('added'));
            }).fail(function (xhr) {
                showError($err, errorText(xhr));
                $btn.prop('disabled', false);
            });
        });
    }

    $('#cart-list').on('click', '.cart-remove', function () {
        var $item = $(this).closest('li');
        post('/_ajax/cart-remove.php', { id: $(this).data('id') }).done(function (res) {
            $item.remove();
            $('#cart-count').text(res.count);
            $('#cart-total').text(res.total);
            if (res.count === 0) {
                $('#cart-body').prop('hidden', true);
                $('#cart-empty').prop('hidden', false).attr('tabindex', -1).trigger('focus');
            } else {
                $('#cart-list li:first .cart-remove').trigger('focus');
            }
        });
    });

    var $checkout = $('#checkout-form');
    $checkout.find('input[name="method"]').on('change', function () {
        $('#card-panel').prop('hidden', this.value !== 'card');
    });
    $checkout.on('submit', function (event) {
        event.preventDefault();
        var $error = $checkout.find('.form-error').prop('hidden', true);
        var $btn = $checkout.find('[type="submit"]').prop('disabled', true);
        post('/_ajax/checkout.php', $checkout.serialize()).done(function (res) {
            window.location.href = res.redirect;
        }).fail(function (xhr) {
            showError($error, errorText(xhr));
            $btn.prop('disabled', false);
        });
    });

    $('#draw-btn').on('click', function () {
        var $btn = $(this).prop('disabled', true).text(tr('drawing'));
        var $result = $('#draw-result');
        post('/_ajax/draw.php', { id: $('#draw-area').data('order') }).done(function (res) {
            var $tray = $('#drawn-balls').empty();
            $('#drawn-box').prop('hidden', false);
            $.each(res.drawn, function (i, n) {
                setTimeout(function () {
                    $('<span class="ball ball-drawn ball-pop">').text(n).attr('data-n', n).appendTo($tray);
                    if (i === res.drawn.length - 1) { reveal(res); }
                }, reduceMotion ? 0 : i * 700);
            });
            $btn.remove();
        }).fail(function (xhr) {
            $btn.prop('disabled', false);
            $result.text(errorText(xhr));
        });

        function reveal(res) {
            var drawn = res.drawn;
            $.each(res.plays, function (i, play) {
                var $row = $('[data-play="' + play.id + '"]');
                $row.find('.ball').each(function () {
                    if (drawn.indexOf($(this).data('n')) !== -1) {
                        $(this).addClass('ball-hit').append($('<span class="sr-only">').text(' ' + tr('hit')));
                    }
                });
                $row.find('.play-result').text(tr('matches', { n: play.matches }) + ' · ' + play.prize);
            });
            $result.text(res.plays.some(function (p) { return p.won; }) ? tr('won', { total: res.total }) : tr('lost'));
        }
    });
})(jQuery);
