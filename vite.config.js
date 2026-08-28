import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import fs from 'node:fs/promises';
import path from 'node:path';
const BUILD_DIR = 'assets';


export default defineConfig({
    plugins: [
        { name: 'move-manifest-files', closeBundle },
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            assets: [
                ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ],
            refresh: true,
            fonts: [
                // bunny('Instrument Sans', {
                //     weights: [400, 500, 600],
                // }),
            ],
            buildDirectory: BUILD_DIR,
            hotFile: 'resources/hot',
        }),
        tailwindcss(),
    ],
    resolve: {
        alias: {
            '@'    : path.resolve(__dirname, './resources/js'),
            '@css'    : path.resolve(__dirname, './resources/css'),
        },
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
    build: {
        assetsDir: '',
        outDir: 'public/' + BUILD_DIR,
    },
});



async function closeBundle() {

    const filesToMove = [
        { source: 'manifest.json', label: 'manifest.json' },
        { source: 'fonts-manifest.json', label: 'fonts-manifest.json' },
    ];

    const VITE_META_DIR = path.resolve(__dirname, 'public', BUILD_DIR);
    const DESTINATION_DIR = path.resolve(__dirname, 'resources');

    for (const { source, label } of filesToMove) {

        const sourcePath = path.join(VITE_META_DIR, source);
        const destPath = path.join(DESTINATION_DIR, source);

        try {
            await fs.access(sourcePath);
        } catch {
            console.warn(`[move-manifest] Caution: "${label}" doesn't exist in "${sourcePath}".`);

            try {
                await fs.unlink(destPath);
                console.log(`[move-manifest] Info: deleting the "${label}" file in "${sourcePath}" is done.`);
            } catch {
                // لا مشكلة لو ما كانت موجودة أصلًا
            }

            console.log('');
            continue;
        }

        // حذف النسخة القديمة بالوجهة قبل النقل (تحديث دائم مع كل بناء)
        try {

            await fs.unlink(destPath);
            console.log(`[move-manifest] Info: deleting the "${label}" file in "${destPath}" is done.`);

        } catch {
            // لا مشكلة لو ما كانت موجودة أصلًا
        }

        await fs.rename(sourcePath, destPath);
        console.log(`[move-manifest] Updating and transferring "${label}" is done.`);

        console.log('');

    }

}
