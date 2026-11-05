<?php

declare(strict_types=1);

namespace Modules\Media\Tests\Unit\Actions;

use Modules\Media\Actions\CloudFront\GetCloudFrontSignedUrlAction;
use Modules\Media\Actions\Image\Merge as ImageMerge;
use Modules\Media\Actions\Image\SvgExistsAction;
use Modules\Media\Actions\S3\BaseS3Action;
use Modules\Media\Actions\S3\CheckFileExistsAction;
use Modules\Media\Actions\S3\DeleteFileAction;
use Modules\Media\Actions\S3\GetFileInfoAction;
use Modules\Media\Actions\S3\UploadFileAction;
use Modules\Media\Actions\Video\ConvertVideoAction;
use Modules\Media\Actions\Video\ConvertVideoByConvertDataAction;
use Modules\Media\Actions\Video\ConvertVideoByMediaConvertAction;
use Modules\Media\Actions\Video\GetVideoDurationAction;
use Modules\Media\Actions\Video\GetVideoFrameContentAction;
use Modules\Media\Actions\Video\GetVideoScreenshotAction;
use Modules\Media\Tests\TestCase;
use PHPUnit\Framework\Assert;
use ReflectionClass;

uses(TestCase::class);

describe('Media Actions Coverage', function () {
    describe('Image Merge Action', function () {
        it('can be instantiated', function (): void {
            Assert::assertSame(ImageMerge::class, (new ImageMerge)::class);
        });

        it('has handle method', function (): void {
            expect((new ReflectionClass(ImageMerge::class))->hasMethod('handle'))->toBeTrue();
        });

        it('has execute method', function (): void {
            expect((new ReflectionClass(ImageMerge::class))->hasMethod('execute'))->toBeTrue();
        });

        it('uses QueueableAction trait', function (): void {
<<<<<<< HEAD
            assertMediaUsesQueueableAction(ImageMerge::class);
        });

        it('uses strict types', function (): void {
            assertMediaDeclaresStrictTypes(ImageMerge::class);
=======
            TestCase::assertMediaUsesQueueableAction(ImageMerge::class);
        });

        it('uses strict types', function (): void {
            TestCase::assertMediaDeclaresStrictTypes(ImageMerge::class);
>>>>>>> laraxot/dev
        });
    });

    describe('SvgExistsAction', function () {
        it('can be instantiated', function (): void {
            Assert::assertSame(SvgExistsAction::class, (new SvgExistsAction)::class);
        });

        it('can be resolved from container', function (): void {})->todo();

        it('has execute method', function (): void {
            expect((new ReflectionClass(SvgExistsAction::class))->hasMethod('execute'))->toBeTrue();
        });

        it('uses strict types', function (): void {
<<<<<<< HEAD
            assertMediaDeclaresStrictTypes(SvgExistsAction::class);
=======
            TestCase::assertMediaDeclaresStrictTypes(SvgExistsAction::class);
>>>>>>> laraxot/dev
        });
    });

    describe('ConvertVideoAction', function () {
        it('can be instantiated', function (): void {
            Assert::assertSame(ConvertVideoAction::class, (new ConvertVideoAction)::class);
        });

        it('can be resolved from container', function (): void {})->todo();

        it('has execute method', function (): void {
            expect((new ReflectionClass(ConvertVideoAction::class))->hasMethod('execute'))->toBeTrue();
        });

        it('uses QueueableAction trait', function (): void {
<<<<<<< HEAD
            assertMediaUsesQueueableAction(ConvertVideoAction::class);
        });

        it('uses strict types', function (): void {
            assertMediaDeclaresStrictTypes(ConvertVideoAction::class);
=======
            TestCase::assertMediaUsesQueueableAction(ConvertVideoAction::class);
        });

        it('uses strict types', function (): void {
            TestCase::assertMediaDeclaresStrictTypes(ConvertVideoAction::class);
>>>>>>> laraxot/dev
        });
    });

    describe('ConvertVideoByConvertDataAction', function () {
        it('can be instantiated', function (): void {
            Assert::assertSame(ConvertVideoByConvertDataAction::class, (new ConvertVideoByConvertDataAction)::class);
        });

        it('has execute method', function (): void {
            expect((new ReflectionClass(ConvertVideoByConvertDataAction::class))->hasMethod('execute'))->toBeTrue();
        });

        it('uses QueueableAction trait', function (): void {
<<<<<<< HEAD
            assertMediaUsesQueueableAction(ConvertVideoByConvertDataAction::class);
        });

        it('uses strict types', function (): void {
            assertMediaDeclaresStrictTypes(ConvertVideoByConvertDataAction::class);
=======
            TestCase::assertMediaUsesQueueableAction(ConvertVideoByConvertDataAction::class);
        });

        it('uses strict types', function (): void {
            TestCase::assertMediaDeclaresStrictTypes(ConvertVideoByConvertDataAction::class);
>>>>>>> laraxot/dev
        });
    });

    describe('ConvertVideoByMediaConvertAction', function () {
        it('can be instantiated', function (): void {
            Assert::assertSame(ConvertVideoByMediaConvertAction::class, (new ConvertVideoByMediaConvertAction)::class);
        });

        it('has execute method', function (): void {
            expect((new ReflectionClass(ConvertVideoByMediaConvertAction::class))->hasMethod('execute'))->toBeTrue();
        });

        it('uses QueueableAction trait', function (): void {
<<<<<<< HEAD
            assertMediaUsesQueueableAction(ConvertVideoByMediaConvertAction::class);
        });

        it('uses strict types', function (): void {
            assertMediaDeclaresStrictTypes(ConvertVideoByMediaConvertAction::class);
=======
            TestCase::assertMediaUsesQueueableAction(ConvertVideoByMediaConvertAction::class);
        });

        it('uses strict types', function (): void {
            TestCase::assertMediaDeclaresStrictTypes(ConvertVideoByMediaConvertAction::class);
>>>>>>> laraxot/dev
        });
    });

    describe('GetVideoScreenshotAction', function () {
        it('can be instantiated', function (): void {
            Assert::assertSame(GetVideoScreenshotAction::class, (new GetVideoScreenshotAction)::class);
        });

        it('has backoff property', function (): void {
            expect((new ReflectionClass(GetVideoScreenshotAction::class))->hasProperty('backoff'))->toBeTrue();
        });

        it('uses QueueableAction trait', function (): void {
<<<<<<< HEAD
            assertMediaUsesQueueableAction(GetVideoScreenshotAction::class);
        });

        it('uses strict types', function (): void {
            assertMediaDeclaresStrictTypes(GetVideoScreenshotAction::class);
=======
            TestCase::assertMediaUsesQueueableAction(GetVideoScreenshotAction::class);
        });

        it('uses strict types', function (): void {
            TestCase::assertMediaDeclaresStrictTypes(GetVideoScreenshotAction::class);
>>>>>>> laraxot/dev
        });
    });

    describe('GetVideoFrameContentAction', function () {
        it('can be instantiated', function (): void {
            Assert::assertSame(GetVideoFrameContentAction::class, (new GetVideoFrameContentAction)::class);
        });

        it('has execute method', function (): void {
            expect((new ReflectionClass(GetVideoFrameContentAction::class))->hasMethod('execute'))->toBeTrue();
        });

        it('uses QueueableAction trait', function (): void {
<<<<<<< HEAD
            assertMediaUsesQueueableAction(GetVideoFrameContentAction::class);
        });

        it('uses strict types', function (): void {
            assertMediaDeclaresStrictTypes(GetVideoFrameContentAction::class);
=======
            TestCase::assertMediaUsesQueueableAction(GetVideoFrameContentAction::class);
        });

        it('uses strict types', function (): void {
            TestCase::assertMediaDeclaresStrictTypes(GetVideoFrameContentAction::class);
>>>>>>> laraxot/dev
        });
    });

    describe('GetVideoDurationAction', function () {
        it('can be instantiated', function (): void {
            Assert::assertSame(GetVideoDurationAction::class, (new GetVideoDurationAction)::class);
        });

        it('has execute method', function (): void {
            expect((new ReflectionClass(GetVideoDurationAction::class))->hasMethod('execute'))->toBeTrue();
        });

        it('uses QueueableAction trait', function (): void {
<<<<<<< HEAD
            assertMediaUsesQueueableAction(GetVideoDurationAction::class);
        });

        it('uses strict types', function (): void {
            assertMediaDeclaresStrictTypes(GetVideoDurationAction::class);
=======
            TestCase::assertMediaUsesQueueableAction(GetVideoDurationAction::class);
        });

        it('uses strict types', function (): void {
            TestCase::assertMediaDeclaresStrictTypes(GetVideoDurationAction::class);
>>>>>>> laraxot/dev
        });
    });

    describe('S3 UploadFileAction', function () {
        it('has execute method', function (): void {
            expect((new ReflectionClass(UploadFileAction::class))->hasMethod('execute'))->toBeTrue();
        });

        it('extends BaseS3Action', function (): void {
            expect((new ReflectionClass(UploadFileAction::class))->isSubclassOf(BaseS3Action::class))->toBeTrue();
        });

        it('uses strict types', function (): void {
<<<<<<< HEAD
            assertMediaDeclaresStrictTypes(UploadFileAction::class);
=======
            TestCase::assertMediaDeclaresStrictTypes(UploadFileAction::class);
>>>>>>> laraxot/dev
        });
    });

    describe('S3 DeleteFileAction', function () {
        it('has execute method', function (): void {
            expect((new ReflectionClass(DeleteFileAction::class))->hasMethod('execute'))->toBeTrue();
        });

        it('extends BaseS3Action', function (): void {
            expect((new ReflectionClass(DeleteFileAction::class))->isSubclassOf(BaseS3Action::class))->toBeTrue();
        });

        it('uses strict types', function (): void {
<<<<<<< HEAD
            assertMediaDeclaresStrictTypes(DeleteFileAction::class);
=======
            TestCase::assertMediaDeclaresStrictTypes(DeleteFileAction::class);
>>>>>>> laraxot/dev
        });
    });

    describe('S3 GetFileInfoAction', function () {
        it('has execute method', function (): void {
            expect((new ReflectionClass(GetFileInfoAction::class))->hasMethod('execute'))->toBeTrue();
        });

        it('extends BaseS3Action', function (): void {
            expect((new ReflectionClass(GetFileInfoAction::class))->isSubclassOf(BaseS3Action::class))->toBeTrue();
        });

        it('uses strict types', function (): void {
<<<<<<< HEAD
            assertMediaDeclaresStrictTypes(GetFileInfoAction::class);
=======
            TestCase::assertMediaDeclaresStrictTypes(GetFileInfoAction::class);
>>>>>>> laraxot/dev
        });
    });

    describe('S3 CheckFileExistsAction', function () {
        it('has execute method', function (): void {
            expect((new ReflectionClass(CheckFileExistsAction::class))->hasMethod('execute'))->toBeTrue();
        });

        it('extends BaseS3Action', function (): void {
            expect((new ReflectionClass(CheckFileExistsAction::class))->isSubclassOf(BaseS3Action::class))->toBeTrue();
        });

        it('uses strict types', function (): void {
<<<<<<< HEAD
            assertMediaDeclaresStrictTypes(CheckFileExistsAction::class);
=======
            TestCase::assertMediaDeclaresStrictTypes(CheckFileExistsAction::class);
>>>>>>> laraxot/dev
        });
    });

    describe('BaseS3Action', function () {
        it('is abstract', function (): void {
            expect((new ReflectionClass(BaseS3Action::class))->isAbstract())->toBeTrue();
        });

        it('uses QueueableAction trait', function (): void {
<<<<<<< HEAD
            assertMediaUsesQueueableAction(BaseS3Action::class);
        });

        it('uses strict types', function (): void {
            assertMediaDeclaresStrictTypes(BaseS3Action::class);
=======
            TestCase::assertMediaUsesQueueableAction(BaseS3Action::class);
        });

        it('uses strict types', function (): void {
            TestCase::assertMediaDeclaresStrictTypes(BaseS3Action::class);
>>>>>>> laraxot/dev
        });

        it('has s3Client property', function (): void {
            expect((new ReflectionClass(BaseS3Action::class))->hasProperty('s3Client'))->toBeTrue();
        });

        it('has bucketName property', function (): void {
            expect((new ReflectionClass(BaseS3Action::class))->hasProperty('bucketName'))->toBeTrue();
        });

        it('has logger property', function (): void {
            expect((new ReflectionClass(BaseS3Action::class))->hasProperty('logger'))->toBeTrue();
        });
    });

    describe('GetCloudFrontSignedUrlAction', function () {
        it('can be instantiated', function (): void {
            Assert::assertSame(GetCloudFrontSignedUrlAction::class, (new GetCloudFrontSignedUrlAction)::class);
        });

        it('has execute method', function (): void {
            expect((new ReflectionClass(GetCloudFrontSignedUrlAction::class))->hasMethod('execute'))->toBeTrue();
        });

        it('uses QueueableAction trait', function (): void {
<<<<<<< HEAD
            assertMediaUsesQueueableAction(GetCloudFrontSignedUrlAction::class);
        });

        it('uses strict types', function (): void {
            assertMediaDeclaresStrictTypes(GetCloudFrontSignedUrlAction::class);
=======
            TestCase::assertMediaUsesQueueableAction(GetCloudFrontSignedUrlAction::class);
        });

        it('uses strict types', function (): void {
            TestCase::assertMediaDeclaresStrictTypes(GetCloudFrontSignedUrlAction::class);
>>>>>>> laraxot/dev
        });
    });
});
