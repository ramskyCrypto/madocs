<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    /**
     * Semua role yang sudah login boleh melihat listing (isi listing sendiri
     * sudah difilter oleh Document::scopeVisibleTo).
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Document $document): bool
    {
        return match ($user->role) {
            User::ROLE_ADMIN, User::ROLE_STAFF => true,
            User::ROLE_KEPALA_SEKOLAH, User::ROLE_GURU => in_array($document->visibility, [
                Document::VISIBILITY_PUBLIK, Document::VISIBILITY_INTERNAL,
            ]) || $document->user_id === $user->id,
            User::ROLE_SISWA => $document->visibility === Document::VISIBILITY_PUBLIK,
            default => false,
        };
    }

    public function create(User $user): bool
    {
        return $user->canUploadDocuments();
    }

    public function update(User $user, Document $document): bool
    {
        if (in_array($user->role, [User::ROLE_ADMIN, User::ROLE_STAFF], true)) {
            return true;
        }

        if ($user->role === User::ROLE_GURU) {
            return $document->user_id === $user->id;
        }

        return false;
    }

    public function delete(User $user, Document $document): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_STAFF], true);
    }

    public function download(User $user, Document $document): bool
    {
        return $this->view($user, $document);
    }
}
