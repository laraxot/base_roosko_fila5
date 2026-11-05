<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Notify\Datas\EmailAttachmentData;
use Modules\Notify\Datas\EmailData;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

describe('EmailData DTO', function () {
    it('creates with all fields', function () {
        $attachment = new EmailAttachmentData(
            path: '/path/file.pdf',
            name: 'document.pdf',
            mime: 'application/pdf',
        );

        $dto = new EmailData(
            to: 'recipient@example.com',
            subject: 'Test Subject',
            bodyHtml: '<p>HTML Body</p>',
            bodyText: 'Text Body',
            attachments: [$attachment],
            cc: ['cc@example.com'],
            bcc: ['bcc@example.com'],
            replyTo: 'noreply@example.com',
            priority: 'high',
        );

        expect($dto->to)->toBe('recipient@example.com');
        expect($dto->subject)->toBe('Test Subject');
        expect($dto->bodyHtml)->toBe('<p>HTML Body</p>');
        expect($dto->bodyText)->toBe('Text Body');
        expect($dto->attachments)->toHaveCount(1);
        expect($dto->cc)->toBe(['cc@example.com']);
        expect($dto->priority)->toBe('high');
    });

    it('creates with minimal fields', function () {
        $dto = new EmailData(
            to: 'recipient@example.com',
            subject: 'Minimal',
            bodyHtml: '<p>Body</p>',
        );

        expect($dto->to)->toBe('recipient@example.com');
        expect($dto->bodyText)->toBeNull();
        expect($dto->attachments)->toBeEmpty();
        expect($dto->priority)->toBe('normal');
    });

    it('accepts array of recipients', function () {
        $dto = new EmailData(
            to: ['user1@example.com', 'user2@example.com'],
            subject: 'Multi',
            bodyHtml: '<p>Body</p>',
        );

        expect($dto->to)->toBeArray()->toHaveCount(2);
    });

    it('converts to array', function () {
        $dto = new EmailData(
            to: 'test@example.com',
            subject: 'Test',
            bodyHtml: '<p>Body</p>',
        );
        $array = $dto->toArray();

        expect($array)->toHaveKeys(['to', 'subject', 'body_html', 'body_text', 'priority']);
    });

    it('gets primary recipient', function () {
        $dto = new EmailData(
            to: ['first@example.com', 'second@example.com'],
            subject: 'Test',
            bodyHtml: '<p>Body</p>',
        );

        expect($dto->getPrimaryRecipient())->toBe('first@example.com');
    });
});

describe('EmailAttachmentData DTO', function () {
    it('creates with required fields', function () {
        $dto = new EmailAttachmentData(
            path: '/path/to/file.pdf',
            name: 'document.pdf',
            mime: 'application/pdf',
        );

        expect($dto->path)->toBe('/path/to/file.pdf');
        expect($dto->name)->toBe('document.pdf');
        expect($dto->mime)->toBe('application/pdf');
    });

    it('creates with content instead of path', function () {
        $content = base64_encode('file content');
        $dto = new EmailAttachmentData(
            content: $content,
            name: 'document.pdf',
            mime: 'application/pdf',
        );

        expect($dto->content)->toBe($content);
        expect($dto->path)->toBeNull();
    });

    it('validates mime type', function () {
        $dto = new EmailAttachmentData(
            path: '/path/file.pdf',
            name: 'document.pdf',
            mime: 'application/pdf',
        );

        expect($dto->mime)->toBe('application/pdf');
    });

    it('converts to array', function () {
        $dto = new EmailAttachmentData(
            path: '/path/file.pdf',
            name: 'document.pdf',
            mime: 'application/pdf',
        );
        $array = $dto->toArray();

        expect($array)->toHaveKeys(['path', 'name', 'mime']);
    });
});
