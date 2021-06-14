'use strict';

var gulp = require('gulp'),
    concat = require('gulp-concat'),
    uglifycss = require('gulp-uglifycss'),
    rename = require('gulp-rename'),
    flatten = require('gulp-flatten');

gulp.task('build-css', function() {
    return gulp.src([
        'resources/js/components/common/Common.css',
		'resources/js/components/**/*.css'
    ])
	.pipe(concat('primevue.css'))
	.pipe(gulp.dest('dist/resources'))
    .pipe(uglifycss({"uglyComments": true}))
    .pipe(rename('primevue.min.css'))
	.pipe(gulp.dest('dist/resources'));
});

gulp.task('build-themes', function() {
    return gulp.src([
        'public/themes/**/*','!public/themes/soho-*/**/*', '!public/themes/viva-*/**/*',
                        '!public/themes/mira/**/*', '!public/themes/nano/**/*'
    ])
    .pipe(gulp.dest('dist/resources/themes'));
})

gulp.task('images', function() {
    return gulp.src(['resources/js/components/**/images/*.png', 'resources/js/components/**/images/*.gif'])
        .pipe(flatten())
        .pipe(gulp.dest('dist/resources/images'));
});

//Building project with run sequence
gulp.task('build-styles', ['build-css', 'images', 'build-themes']);

