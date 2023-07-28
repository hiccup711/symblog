/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

import 'bootstrap';

import $ from 'jquery';
import Routing from '../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

const routes = require('../public/js/fos_js_routes.json');

Routing.setRoutingData(routes);

$(document).ready(function () {
    $('button.js-replay-comment-btn').on('click', function (element) {
        let replyCards = $('.reply-comment-card');
        if (replyCards.length > 0) {
            replyCards.remove();
            return;
        }
        if ($(this).nextAll('.max-level-info').length === 1) {
            return;
        }
        let postId = $(this).data('post-id');
        let parentId = $(this).data('parent-id');

        $.ajax({
            url: Routing.generate('replay_comment', {
                post_id: postId,
                comment_id: parentId
            }),
            type: 'POST'
        }).done(function (response) {
            $(element.target).after(response)
        }).fail(function (jqXHR, textStatus, errorThrown) {
        })
    });
})