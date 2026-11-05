<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Modules\Notify\Actions\BuildMailMessageAction;
use Modules\Notify\Datas\EmailAttachmentData;
use Modules\Notify\Datas\EmailData;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(BuildMailMessageAction::class);
    Mail::fake();
});

describe('BuildMailMessageAction', function () {
    it('builds mail message from EmailData', function () {
        $emailData = new EmailData(
            to: 'recipient@example.com',
            subject: 'Test Subject',
            bodyHtml: '<p>HTML Body</p>',
            bodyText: 'Text Body',
        );

        $message = $this->action->execute($emailData);

        expect($message)->toBeInstanceOf(Message::class);
    });

    it('adds attachments when provided', function () {
        $attachment = new EmailAttachmentData(
            path: '/path/to/file.pdf',
            name: 'document.pdf',
            mime: 'application/pdf',
        );

        $emailData = new EmailData(
            to: 'recipient@example.com',
            subject: 'With Attachment',
            bodyHtml: '<p>Body</p>',
            attachments: [$attachment],
        );

        $message = $this->action->execute($emailData);

        expect($message)->toBeInstanceOf(Message::class);
    });

    it('handles multiple recipients', function () {
        $emailData = new EmailData(
            to: ['user1@example.com', 'user2@example.com'],
            subject: 'Multi Recipient',
            bodyHtml: '<p>Body</p>',
        );

        $message = $this->action->execute($emailData);

        expect($message)->toBeInstanceOf(Message::class);
    });

    it('sets priority headers', function () {
        $emailData = new EmailData(
            to: 'recipient@example.com',
            subject: 'High Priority',
            bodyHtml: '<p>Body</p>',
            priority: 'high',
        );

        $message = $this->action->execute($emailData);

        expect($message)->toBeInstanceOf(Message::class);
    });

    it('uses text body when HTML not provided', function () {
        $emailData = new EmailData(
            to: 'recipient@example.com',
            subject: 'Text Only',
            bodyText: 'Plain text body',
        );

        $message = $this->action->execute($emailData);

        expect($message)->toBeInstanceOf(Message::class);
    });

    it('handles CC and BCC', function () {
        $emailData = new EmailData(
            to: 'to@example.com',
            cc: ['cc@example.com'],
            bcc: ['bcc@example.com'],
            subject: 'With CC/BCC',
            bodyHtml: '<p>Body</p>',
        );

        $message = $this->action->execute($emailData);

        expect($message)->toBeInstanceOf(Message::class);
    });

    it('sets reply-to address', function () {
        $emailData = new EmailData(
            to: 'recipient@example.com',
            subject: 'Reply To Test',
            bodyHtml: '<p>Body</p>',
            replyTo: 'noreply@example.com',
        );

        $message = $this->action->execute($emailData);

        expect($message)->toBeInstanceOf(Message::class);
    });
});
