import Translation from '../../views/settings/translations/index.vue';
import StorageEdit from '../../views/settings/storage/Edit.vue';
import EmailEdit from '../../views/settings/email/Edit.vue';
import DatabaseBackup from '../../views/settings/database-backup/index.vue';

// Defining route prefix and permission
// According to app_type

export default [
    {
        path: 'translations',
        component: Translation,
        name: `admin.settings.translations.index`,
        meta: {
            requireAuth: true,
            menuParent: "settings",
            menuKey: route => "translations",
            permission: "admin"
        }
    },
    {
        path: 'storage',
        component: StorageEdit,
        name: `admin.settings.storage.index`,
        meta: {
            requireAuth: true,
            menuParent: "settings",
            menuKey: route => "storage_settings",
            permission: "admin"
        }
    },
    {
        path: 'email',
        component: EmailEdit,
        name: `admin.settings.email.index`,
        meta: {
            requireAuth: true,
            menuParent: "settings",
            menuKey: route => "email_settings",
            permission: "admin"
        }
    },
    {
        path: 'database-backup',
        component: DatabaseBackup,
        name: 'admin.settings.database_backup.index',
        meta: {
            requireAuth: true,
            menuParent: "settings",
            menuKey: route => "database_backup",
            permission: "admin"
        }
    },
];
