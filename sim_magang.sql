-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Sep 2026 pada 04.18
-- Versi server: 11.7.2-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sim_magang`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('sistem-informasi-magang-cache-12c6fc06c99a462375eeb3f43dfd832b08ca9e17', 'i:1;', 1789954306),
('sistem-informasi-magang-cache-12c6fc06c99a462375eeb3f43dfd832b08ca9e17:timer', 'i:1789954306;', 1789954306),
('sistem-informasi-magang-cache-52350479c1c4ea664f2621948591a5ad', 'i:3;', 1789955150),
('sistem-informasi-magang-cache-52350479c1c4ea664f2621948591a5ad:timer', 'i:1789955150;', 1789955150),
('sistem-informasi-magang-cache-5c785c036466adea360111aa28563bfd556b5fba', 'i:2;', 1789956044),
('sistem-informasi-magang-cache-5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1789956044;', 1789956044),
('sistem-informasi-magang-cache-admin@diskominfo-tuban.go.id|127.0.0.1', 'i:1;', 1789956044),
('sistem-informasi-magang-cache-admin@diskominfo-tuban.go.id|127.0.0.1:timer', 'i:1789956044;', 1789956044),
('sistem-informasi-magang-cache-global_app_settings', 'a:22:{s:10:\"site_title\";s:87:\"SIMAGANG — Dinas Komunikasi dan Informatika, Statistik dan Persandian Kabupaten Tuban\";s:8:\"app_name\";s:8:\"SIMAGANG\";s:16:\"institution_name\";s:24:\"Diskominfo SP Kab. Tuban\";s:9:\"site_logo\";s:25:\"traveland/images/logo.png\";s:16:\"meta_description\";s:96:\"Portal Tidak Resmi Pendaftaran Magang Diskominfo SP Kab. Tuban. Daftarkan dirimu secara digital!\";s:10:\"hero_badge\";s:31:\"Portal Resmi Pendaftaran Magang\";s:10:\"hero_title\";s:48:\"Membangun Talenta Digital untuk Pelayanan Publik\";s:16:\"hero_description\";s:294:\"SIMAGANG (Sistem Informasi Magang) merupakan portal resmi Diskominfo SP Kabupaten Tuban untuk memfasilitasi pendaftaran dan pengelolaan magang secara digital. Dapatkan pengalaman kerja nyata dan kembangkan kompetensimu melalui proses rekrutmen yang transparan, terintegrasi, dan 100% paperless.\";s:10:\"hero_image\";s:61:\"storage/settings/5aLKEx1NrVQOCUJi1dMT5wOZQDIn6udFDxf4Ogdv.png\";s:11:\"about_title\";s:22:\"SIMAGANG Diskominfo SP\";s:17:\"about_description\";s:139:\"Platform pendaftaran magang resmi untuk Mahasiswa dan Siswa SMK. Seluruh proses dilakukan 100% secara digital, terstruktur, dan transparan.\";s:11:\"about_image\";s:22:\"traveland/images/2.png\";s:15:\"contact_address\";s:59:\"Jl. Mastrip No. 5 A, Sidorejo, Kec. Tuban, Jawa Timur 62315\";s:13:\"contact_email\";s:25:\"diskominfo@tubankab.go.id\";s:13:\"contact_phone\";s:14:\"(0356) 8832697\";s:21:\"contact_working_hours\";s:33:\"Senin - Jum\'at: 07.30 - 16.00 WIB\";s:14:\"social_website\";s:33:\"https://diskominfo.tubankab.go.id\";s:15:\"social_facebook\";s:41:\"https://www.facebook.com/diskominfo.tuban\";s:16:\"social_instagram\";s:39:\"https://www.instagram.com/kominfo.tuban\";s:14:\"social_twitter\";s:35:\"https://twitter.com/DiskominfoTuban\";s:14:\"social_youtube\";s:75:\"https://www.youtube.com/channel/UC7V9cxzD7Gk-K_jxGMbblgA?view_as=subscriber\";s:14:\"maps_embed_url\";s:193:\"https://www.google.com/maps/embed?pb=!4v1788316632382!6m8!1m7!1szab-FoOpFkmJVJ79X0G0Pw!2m2!1d-6.901873934235668!2d112.0440727763729!3f117.32336345271811!4f-6.10453670657121!5f0.4000000000000002\";}', 2104987719);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `failed_jobs`
--

INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(70, 'c1334913-aaf2-4116-99b1-3e353fbc2ebf', 'database', 'default', '{\"uuid\":\"c1334913-aaf2-4116-99b1-3e353fbc2ebf\",\"displayName\":\"App\\\\Mail\\\\ApplicationSubmittedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":19:{s:8:\\\"mailable\\\";O:33:\\\"App\\\\Mail\\\\ApplicationSubmittedMail\\\":3:{s:12:\\\"registration\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:23:\\\"App\\\\Models\\\\Registration\\\";s:2:\\\"id\\\";i:999;s:9:\\\"relations\\\";a:2:{i:0;s:4:\\\"user\\\";i:1;s:8:\\\"position\\\";}s:10:\\\"connection\\\";N;s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:24:\\\"bagusdwijunior@gmail.com\\\";}}s:6:\\\"mailer\\\";s:6:\\\"resend\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:13:\\\"debounceOwner\\\";s:0:\\\"\\\";s:15:\\\"uniqueLockOwner\\\";s:0:\\\"\\\";s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1789006037,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\ModelNotFoundException: No query results for model [App\\Models\\Registration]. in C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php:786\nStack trace:\n#0 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(112): Illuminate\\Database\\Eloquent\\Builder->firstOrFail()\n#1 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(63): App\\Mail\\ApplicationSubmittedMail->restoreModel(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#2 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesModels.php(98): App\\Mail\\ApplicationSubmittedMail->getRestoredPropertyValue(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#3 [internal function]: App\\Mail\\ApplicationSubmittedMail->__unserialize(Array)\n#4 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(116): unserialize(\'O:34:\"Illuminat...\')\n#5 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(73): Illuminate\\Queue\\CallQueuedHandler->getCommand(Array)\n#6 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#7 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(559): Illuminate\\Queue\\Jobs\\Job->fire()\n#8 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(505): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#9 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(257): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#10 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#11 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#12 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#13 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::{closure:Illuminate\\Container\\BoundMethod::call():35}()\n#14 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#15 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#16 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(800): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#17 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(292): Illuminate\\Container\\Container->call(Array)\n#18 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Command\\Command.php(284): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Laravel\\Pao\\Laravel\\PaoOutputStyle))\n#19 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(261): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Laravel\\Pao\\Laravel\\PaoOutputStyle))\n#20 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Application.php(1144): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#21 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Application.php(379): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#22 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Application.php(218): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#23 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#24 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1242): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#25 C:\\xampp\\htdocs\\simang\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#26 {main}', '2026-09-10 02:09:00'),
(71, '87e9046c-351b-4a9a-b5f9-e5b3b7b0cc3a', 'database', 'default', '{\"uuid\":\"87e9046c-351b-4a9a-b5f9-e5b3b7b0cc3a\",\"displayName\":\"App\\\\Mail\\\\ApplicationReviewedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":19:{s:8:\\\"mailable\\\";O:32:\\\"App\\\\Mail\\\\ApplicationReviewedMail\\\":3:{s:12:\\\"registration\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:23:\\\"App\\\\Models\\\\Registration\\\";s:2:\\\"id\\\";i:999;s:9:\\\"relations\\\";a:2:{i:0;s:4:\\\"user\\\";i:1;s:8:\\\"position\\\";}s:10:\\\"connection\\\";N;s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:24:\\\"bagusdwijunior@gmail.com\\\";}}s:6:\\\"mailer\\\";s:6:\\\"resend\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:13:\\\"debounceOwner\\\";s:0:\\\"\\\";s:15:\\\"uniqueLockOwner\\\";s:0:\\\"\\\";s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1789006037,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\ModelNotFoundException: No query results for model [App\\Models\\Registration]. in C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php:786\nStack trace:\n#0 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(112): Illuminate\\Database\\Eloquent\\Builder->firstOrFail()\n#1 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(63): App\\Mail\\ApplicationReviewedMail->restoreModel(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#2 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesModels.php(98): App\\Mail\\ApplicationReviewedMail->getRestoredPropertyValue(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#3 [internal function]: App\\Mail\\ApplicationReviewedMail->__unserialize(Array)\n#4 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(116): unserialize(\'O:34:\"Illuminat...\')\n#5 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(73): Illuminate\\Queue\\CallQueuedHandler->getCommand(Array)\n#6 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#7 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(559): Illuminate\\Queue\\Jobs\\Job->fire()\n#8 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(505): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#9 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(257): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#10 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#11 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#12 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#13 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::{closure:Illuminate\\Container\\BoundMethod::call():35}()\n#14 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#15 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#16 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(800): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#17 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(292): Illuminate\\Container\\Container->call(Array)\n#18 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Command\\Command.php(284): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Laravel\\Pao\\Laravel\\PaoOutputStyle))\n#19 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(261): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Laravel\\Pao\\Laravel\\PaoOutputStyle))\n#20 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Application.php(1144): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#21 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Application.php(379): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#22 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Application.php(218): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#23 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#24 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1242): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#25 C:\\xampp\\htdocs\\simang\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#26 {main}', '2026-09-10 02:09:00'),
(72, 'bb1a05ea-8d0f-4fe7-9be1-63a482fbc7cf', 'database', 'default', '{\"uuid\":\"bb1a05ea-8d0f-4fe7-9be1-63a482fbc7cf\",\"displayName\":\"App\\\\Mail\\\\ApplicationAcceptedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":19:{s:8:\\\"mailable\\\";O:32:\\\"App\\\\Mail\\\\ApplicationAcceptedMail\\\":3:{s:12:\\\"registration\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:23:\\\"App\\\\Models\\\\Registration\\\";s:2:\\\"id\\\";i:999;s:9:\\\"relations\\\";a:2:{i:0;s:4:\\\"user\\\";i:1;s:8:\\\"position\\\";}s:10:\\\"connection\\\";N;s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:24:\\\"bagusdwijunior@gmail.com\\\";}}s:6:\\\"mailer\\\";s:6:\\\"resend\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:13:\\\"debounceOwner\\\";s:0:\\\"\\\";s:15:\\\"uniqueLockOwner\\\";s:0:\\\"\\\";s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1789006037,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\ModelNotFoundException: No query results for model [App\\Models\\Registration]. in C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php:786\nStack trace:\n#0 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(112): Illuminate\\Database\\Eloquent\\Builder->firstOrFail()\n#1 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(63): App\\Mail\\ApplicationAcceptedMail->restoreModel(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#2 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesModels.php(98): App\\Mail\\ApplicationAcceptedMail->getRestoredPropertyValue(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#3 [internal function]: App\\Mail\\ApplicationAcceptedMail->__unserialize(Array)\n#4 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(116): unserialize(\'O:34:\"Illuminat...\')\n#5 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(73): Illuminate\\Queue\\CallQueuedHandler->getCommand(Array)\n#6 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#7 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(559): Illuminate\\Queue\\Jobs\\Job->fire()\n#8 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(505): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#9 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(257): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#10 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#11 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#12 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#13 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::{closure:Illuminate\\Container\\BoundMethod::call():35}()\n#14 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#15 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#16 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(800): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#17 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(292): Illuminate\\Container\\Container->call(Array)\n#18 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Command\\Command.php(284): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Laravel\\Pao\\Laravel\\PaoOutputStyle))\n#19 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(261): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Laravel\\Pao\\Laravel\\PaoOutputStyle))\n#20 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Application.php(1144): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#21 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Application.php(379): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#22 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Application.php(218): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#23 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#24 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1242): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#25 C:\\xampp\\htdocs\\simang\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#26 {main}', '2026-09-10 02:09:00'),
(73, 'd33ca25a-4ff0-4cd1-9059-5f40be6bc990', 'database', 'default', '{\"uuid\":\"d33ca25a-4ff0-4cd1-9059-5f40be6bc990\",\"displayName\":\"App\\\\Mail\\\\ApplicationRejectedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":19:{s:8:\\\"mailable\\\";O:32:\\\"App\\\\Mail\\\\ApplicationRejectedMail\\\":4:{s:12:\\\"registration\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:23:\\\"App\\\\Models\\\\Registration\\\";s:2:\\\"id\\\";i:999;s:9:\\\"relations\\\";a:2:{i:0;s:4:\\\"user\\\";i:1;s:8:\\\"position\\\";}s:10:\\\"connection\\\";N;s:15:\\\"collectionClass\\\";N;}s:12:\\\"catatanAdmin\\\";s:88:\\\"Mohon maaf, kuota pendaftaran untuk divisi yang Anda pilih telah penuh pada periode ini.\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:24:\\\"bagusdwijunior@gmail.com\\\";}}s:6:\\\"mailer\\\";s:6:\\\"resend\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:13:\\\"debounceOwner\\\";s:0:\\\"\\\";s:15:\\\"uniqueLockOwner\\\";s:0:\\\"\\\";s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1789006037,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\ModelNotFoundException: No query results for model [App\\Models\\Registration]. in C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php:786\nStack trace:\n#0 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(112): Illuminate\\Database\\Eloquent\\Builder->firstOrFail()\n#1 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(63): App\\Mail\\ApplicationRejectedMail->restoreModel(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#2 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesModels.php(98): App\\Mail\\ApplicationRejectedMail->getRestoredPropertyValue(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#3 [internal function]: App\\Mail\\ApplicationRejectedMail->__unserialize(Array)\n#4 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(116): unserialize(\'O:34:\"Illuminat...\')\n#5 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(73): Illuminate\\Queue\\CallQueuedHandler->getCommand(Array)\n#6 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#7 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(559): Illuminate\\Queue\\Jobs\\Job->fire()\n#8 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(505): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#9 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(257): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#10 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#11 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#12 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#13 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::{closure:Illuminate\\Container\\BoundMethod::call():35}()\n#14 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#15 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#16 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(800): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#17 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(292): Illuminate\\Container\\Container->call(Array)\n#18 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Command\\Command.php(284): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Laravel\\Pao\\Laravel\\PaoOutputStyle))\n#19 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(261): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Laravel\\Pao\\Laravel\\PaoOutputStyle))\n#20 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Application.php(1144): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#21 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Application.php(379): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#22 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Application.php(218): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#23 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#24 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1242): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#25 C:\\xampp\\htdocs\\simang\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#26 {main}', '2026-09-10 02:09:00'),
(74, 'f77300fe-b03b-4b10-b255-6418b877aef0', 'database', 'default', '{\"uuid\":\"f77300fe-b03b-4b10-b255-6418b877aef0\",\"displayName\":\"App\\\\Mail\\\\ReplyLetterAvailableMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":19:{s:8:\\\"mailable\\\";O:33:\\\"App\\\\Mail\\\\ReplyLetterAvailableMail\\\":3:{s:12:\\\"registration\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:23:\\\"App\\\\Models\\\\Registration\\\";s:2:\\\"id\\\";i:999;s:9:\\\"relations\\\";a:2:{i:0;s:4:\\\"user\\\";i:1;s:8:\\\"position\\\";}s:10:\\\"connection\\\";N;s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:24:\\\"bagusdwijunior@gmail.com\\\";}}s:6:\\\"mailer\\\";s:6:\\\"resend\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:13:\\\"debounceOwner\\\";s:0:\\\"\\\";s:15:\\\"uniqueLockOwner\\\";s:0:\\\"\\\";s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1789006037,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\ModelNotFoundException: No query results for model [App\\Models\\Registration]. in C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php:786\nStack trace:\n#0 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(112): Illuminate\\Database\\Eloquent\\Builder->firstOrFail()\n#1 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(63): App\\Mail\\ReplyLetterAvailableMail->restoreModel(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#2 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesModels.php(98): App\\Mail\\ReplyLetterAvailableMail->getRestoredPropertyValue(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#3 [internal function]: App\\Mail\\ReplyLetterAvailableMail->__unserialize(Array)\n#4 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(116): unserialize(\'O:34:\"Illuminat...\')\n#5 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(73): Illuminate\\Queue\\CallQueuedHandler->getCommand(Array)\n#6 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#7 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(559): Illuminate\\Queue\\Jobs\\Job->fire()\n#8 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(505): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#9 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(257): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#10 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#11 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#12 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#13 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::{closure:Illuminate\\Container\\BoundMethod::call():35}()\n#14 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#15 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#16 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(800): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#17 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(292): Illuminate\\Container\\Container->call(Array)\n#18 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Command\\Command.php(284): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Laravel\\Pao\\Laravel\\PaoOutputStyle))\n#19 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(261): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Laravel\\Pao\\Laravel\\PaoOutputStyle))\n#20 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Application.php(1144): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#21 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Application.php(379): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#22 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Application.php(218): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#23 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#24 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1242): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#25 C:\\xampp\\htdocs\\simang\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#26 {main}', '2026-09-10 02:09:00'),
(75, '717c223e-0628-4277-b5bd-88973f0ff988', 'database', 'default', '{\"uuid\":\"717c223e-0628-4277-b5bd-88973f0ff988\",\"displayName\":\"App\\\\Notifications\\\\InternDeactivatedNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:999;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";N;s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:47:\\\"App\\\\Notifications\\\\InternDeactivatedNotification\\\":3:{s:12:\\\"registration\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:23:\\\"App\\\\Models\\\\Registration\\\";s:2:\\\"id\\\";i:999;s:9:\\\"relations\\\";a:2:{i:0;s:4:\\\"user\\\";i:1;s:8:\\\"position\\\";}s:10:\\\"connection\\\";N;s:15:\\\"collectionClass\\\";N;}s:19:\\\"catatanPenonaktifan\\\";s:67:\\\"Masa pelaksanaan magang telah diselesaikan dengan baik (Completed).\\\";s:2:\\\"id\\\";s:36:\\\"6466e0de-476c-4a74-98c6-dd4a2a1f5853\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\",\"batchId\":null},\"createdAt\":1789006037,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\ModelNotFoundException: No query results for model [App\\Models\\Registration]. in C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php:786\nStack trace:\n#0 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(112): Illuminate\\Database\\Eloquent\\Builder->firstOrFail()\n#1 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(63): Illuminate\\Notifications\\Notification->restoreModel(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#2 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesModels.php(98): Illuminate\\Notifications\\Notification->getRestoredPropertyValue(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#3 [internal function]: Illuminate\\Notifications\\Notification->__unserialize(Array)\n#4 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(116): unserialize(\'O:48:\"Illuminat...\')\n#5 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(73): Illuminate\\Queue\\CallQueuedHandler->getCommand(Array)\n#6 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#7 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(559): Illuminate\\Queue\\Jobs\\Job->fire()\n#8 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(505): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#9 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(257): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#10 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#11 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#12 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#13 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::{closure:Illuminate\\Container\\BoundMethod::call():35}()\n#14 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#15 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#16 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(800): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#17 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(292): Illuminate\\Container\\Container->call(Array)\n#18 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Command\\Command.php(284): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Laravel\\Pao\\Laravel\\PaoOutputStyle))\n#19 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(261): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Laravel\\Pao\\Laravel\\PaoOutputStyle))\n#20 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Application.php(1144): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#21 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Application.php(379): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#22 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Application.php(218): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#23 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#24 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1242): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#25 C:\\xampp\\htdocs\\simang\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#26 {main}', '2026-09-10 02:09:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(127, 'default', '{\"uuid\":\"0844cfea-ec0c-43b6-aa50-ce93b18989f6\",\"displayName\":\"App\\\\Notifications\\\\ApplicationSubmittedNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:21;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:50:\\\"App\\\\Notifications\\\\ApplicationSubmittedNotification\\\":2:{s:12:\\\"registration\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:23:\\\"App\\\\Models\\\\Registration\\\";s:2:\\\"id\\\";i:25;s:9:\\\"relations\\\";a:2:{i:0;s:8:\\\"position\\\";i:1;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"2b1a98a9-a9a8-487d-a3ce-d15d96682a96\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\",\"batchId\":null},\"createdAt\":1789955125,\"delay\":null}', 0, NULL, 1789955125, 1789955125),
(128, 'default', '{\"uuid\":\"061588d6-336a-4b08-b40e-9d29dd1051d5\",\"displayName\":\"App\\\\Notifications\\\\ApplicationStatusUpdatedNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:21;}s:9:\\\"relations\\\";a:1:{i:0;s:7:\\\"profile\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:54:\\\"App\\\\Notifications\\\\ApplicationStatusUpdatedNotification\\\":3:{s:12:\\\"registration\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:23:\\\"App\\\\Models\\\\Registration\\\";s:2:\\\"id\\\";i:25;s:9:\\\"relations\\\";a:3:{i:0;s:4:\\\"user\\\";i:1;s:12:\\\"user.profile\\\";i:2;s:8:\\\"position\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"catatanAdmin\\\";s:8:\\\"oke done\\\";s:2:\\\"id\\\";s:36:\\\"6ea988be-3b6a-4d3a-91d5-8473d9ec9b8f\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\",\"batchId\":null},\"createdAt\":1789955202,\"delay\":null}', 0, NULL, 1789955202, 1789955202),
(129, 'default', '{\"uuid\":\"d77f689d-6d96-4859-8a77-093d696a5b41\",\"displayName\":\"App\\\\Mail\\\\ReplyLetterAvailableMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":19:{s:8:\\\"mailable\\\";O:33:\\\"App\\\\Mail\\\\ReplyLetterAvailableMail\\\":3:{s:12:\\\"registration\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:23:\\\"App\\\\Models\\\\Registration\\\";s:2:\\\"id\\\";i:25;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:24:\\\"bagusdwijunior@gmail.com\\\";}}s:6:\\\"mailer\\\";s:6:\\\"resend\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:13:\\\"debounceOwner\\\";s:0:\\\"\\\";s:15:\\\"uniqueLockOwner\\\";s:0:\\\"\\\";s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1789955215,\"delay\":null}', 0, NULL, 1789955215, 1789955215),
(130, 'default', '{\"uuid\":\"3070327b-7779-4406-af0c-1843ad098196\",\"displayName\":\"App\\\\Mail\\\\ReplyLetterAvailableMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":19:{s:8:\\\"mailable\\\";O:33:\\\"App\\\\Mail\\\\ReplyLetterAvailableMail\\\":3:{s:12:\\\"registration\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:23:\\\"App\\\\Models\\\\Registration\\\";s:2:\\\"id\\\";i:25;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:24:\\\"bagusdwijunior@gmail.com\\\";}}s:6:\\\"mailer\\\";s:6:\\\"resend\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:13:\\\"debounceOwner\\\";s:0:\\\"\\\";s:15:\\\"uniqueLockOwner\\\";s:0:\\\"\\\";s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1789955485,\"delay\":null}', 0, NULL, 1789955485, 1789955485);

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `landing_contents`
--

CREATE TABLE `landing_contents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `section` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `landing_contents`
--

INSERT INTO `landing_contents` (`id`, `section`, `title`, `description`, `icon`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'about', '100% Digital & Paperless', 'Seluruh berkas pendaftaran, seleksi, hingga verifikasi magang dilakukan secara digital tanpa membutuhkan berkas fisik.', 'bi-laptop', 1, 1, '2026-09-13 12:56:52', '2026-09-14 01:37:01'),
(2, 'about', 'Pemantauan Real-Time', 'Pantau pergerakan status verifikasi berkas, jadwal seleksi, hingga pengumuman kelulusan secara transparan langsung dari dasbor peserta.', 'bi-shield-check', 2, 1, '2026-09-13 12:56:52', '2026-09-14 01:37:48'),
(3, 'about', 'Otomatisasi Dokumen Legal', 'Penerbitan Surat Balasan dan Sertifikat Magang berformat PDF resmi terotomatisasi penuh, mempercepat proses administrasi kampus atau sekolah.', 'bi-people', 3, 1, '2026-09-13 12:56:52', '2026-09-14 01:38:54'),
(4, 'advantage', 'Keterlibatan Proyek E-Government', 'Dapatkan pengalaman nyata membangun infrastruktur digital, aplikasi pelayanan publik, dan tata kelola data pemerintahan Kabupaten Tuban.', 'bi-briefcase', 1, 1, '2026-09-13 12:56:52', '2026-09-14 01:41:08'),
(5, 'advantage', 'Mentoring Praktisi Ahli', 'Dibimbing langsung dan intensif oleh ASN serta tim teknis profesional yang berdedikasi tinggi di bidang teknologi informasi.', 'bi-award', 2, 1, '2026-09-13 12:56:52', '2026-09-14 01:41:32'),
(6, 'advantage', 'Portofolio Standar Industri', 'Hasil kerja selama masa magang dapat diubah menjadi studi kasus konkret yang memperkuat nilai jual CV dan portofolio karier profesionalmu.', 'bi-building', 3, 1, '2026-09-13 12:56:52', '2026-09-14 01:42:00'),
(7, 'advantage', 'Ekosistem Kerja Profesional', 'Rasakan langsung dinamika instansi pemerintah dengan budaya kerja kolaboratif, disiplin, dan berorientasi penuh pada solusi masyarakat.', 'bi-calendar-check', 4, 1, '2026-09-13 12:56:52', '2026-09-14 01:42:24'),
(8, 'workflow', 'Registrasi Akun', 'Buat akun peserta magang di SIMAGANG dengan melengkapi email dan identitas dasar.', 'bi-person-plus', 1, 1, '2026-09-13 12:56:52', '2026-09-13 12:56:52'),
(9, 'workflow', 'Pengajuan & Unggah Berkas', 'Pilih posisi magang yang diminati dan unggah dokumen persyaratan seperti Surat Pengantar dan Proposal.', 'bi-file-earmark-arrow-up', 2, 1, '2026-09-13 12:56:52', '2026-09-13 12:56:52'),
(10, 'workflow', 'Verifikasi & Seleksi', 'Tim verifikator mengevaluasi kelengkapan berkas dan ketersediaan kuota pembimbing.', 'bi-clipboard-check', 3, 1, '2026-09-13 12:56:52', '2026-09-13 12:56:52'),
(11, 'workflow', 'Penerimaan & Pelaksanaan', 'Unduh surat balasan resmi dari portal SIMAGANG dan mulai pelaksanaan magang sesuai jadwal.', 'bi-check-circle', 4, 1, '2026-09-13 12:56:52', '2026-09-13 12:56:52'),
(12, 'faq', 'Siapa saja yang dapat mendaftar magang di Diskominfo SP Tuban?', 'Program magang ini terbuka untuk Siswa SMK/SMA dan Mahasiswa Perguruan Tinggi yang membutuhkan Praktik Kerja Lapangan (PKL) atau Magang Akademik.', 'bi-question-circle', 1, 1, '2026-09-13 12:56:52', '2026-09-13 12:56:52'),
(13, 'faq', 'Apakah pendaftaran magang dipungut biaya?', 'Tidak ada biaya sama sekali. Seluruh proses pendaftaran dan pelaksanaan magang di SIMAGANG adalah 100% GRATIS.', 'bi-cash-stack', 2, 1, '2026-09-13 12:56:52', '2026-09-13 12:56:52'),
(14, 'faq', 'Berapa lama durasi pelaksanaan magang?', 'Durasi magang menyesuaikan ketentuan instansi pendidikan pemohon, umumnya berkisar antara 1 hingga 6 bulan.', 'bi-clock', 3, 1, '2026-09-13 12:56:52', '2026-09-13 12:56:52'),
(15, 'faq', 'Bagaimana cara mengetahui status penerimaan magang?', 'Peserta dapat memantau perubahan status secara real-time melalui dashboard akun SIMAGANG dan email notifikasi.', 'bi-info-circle', 4, 1, '2026-09-13 12:56:52', '2026-09-13 12:56:52'),
(17, 'about', 'Integrasi Data Terpusat', 'Sistem tunggal yang memisahkan dan menyesuaikan alur pendaftaran antara Mahasiswa dan Siswa SMK secara cerdas dan akurat.', 'bi-shield-check', 4, 1, '2026-09-14 01:39:55', '2026-09-14 04:23:34');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_08_05_000010_add_role_to_users_table', 1),
(5, '2026_08_05_000020_create_profiles_table', 1),
(6, '2026_08_05_000025_add_missing_columns_to_profiles_table', 1),
(7, '2026_08_05_000030_create_positions_table', 1),
(8, '2026_08_05_000040_create_registrations_table', 1),
(9, '2026_08_05_000045_add_periode_to_registrations_table', 1),
(10, '2026_08_07_000050_add_participant_type_to_profiles_table', 2),
(11, '2026_08_10_000001_add_proposal_magang_path_to_registrations_table', 3),
(12, '2026_08_11_000060_make_nis_nim_nullable_in_profiles_table', 4),
(13, '2026_08_24_000001_revise_positions_unique_indexes_for_soft_deletes', 5),
(14, '2026_08_24_041233_add_nip_and_position_title_to_users_table', 6),
(15, '2026_08_24_041332_add_mentor_fields_to_positions_table', 6),
(16, '2026_08_24_140433_add_active_intern_status_to_registrations_table', 7),
(17, '2026_08_26_094500_drop_dates_from_positions_table', 8),
(18, '2026_09_01_000000_create_surveys_table', 9),
(19, '2026_09_12_210757_create_settings_table', 10),
(20, '2026_09_13_210000_create_landing_contents_table', 11);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('bagusdwijunior@gmail.com', '$2y$12$xK3uINdo89X16FCbcervRusPNZPNLSW1p0Qos38hrIj3SFXtTErHW', '2026-09-04 07:08:45'),
('bagusputra122334@gmail.com', '$2y$12$7bAQrIaVKdQ9if7AqT8wF.HK9P2ocS7xvbCs4ikNygE0U8AfbqjcG', '2026-08-09 19:05:43'),
('diskominfo@tubankab.go.id', '$2y$12$6MTEPq/bkfAyyqh.iGqEJeVBpkLjY4pt9gl1eMNi1wqf0Ux.70BiK', '2026-09-04 07:01:43'),
('dpmft@unesa.ac.id', '$2y$12$j7Je0wO1yna418y3U9yDP..k9fgX6/D4cbKMOHP8OzTxWyzxrzwMK', '2026-08-26 05:06:44');

-- --------------------------------------------------------

--
-- Struktur dari tabel `positions`
--

CREATE TABLE `positions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_posisi` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `deskripsi` text NOT NULL,
  `kualifikasi` text DEFAULT NULL,
  `kuota` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `mentor_name` varchar(255) DEFAULT NULL,
  `mentor_nip` varchar(30) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'aktif',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `positions`
--

INSERT INTO `positions` (`id`, `nama_posisi`, `slug`, `deskripsi`, `kualifikasi`, `kuota`, `mentor_name`, `mentor_nip`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Sroll tt', 'spamlike', 'pokoknya kalau ada konten langsung like saja oke', 'punya hp', 1, 'Drs. Eko Prasetyo, M.Kom', '19820315 200801 1 004', 'aktif', '2026-08-09 21:22:22', '2026-08-05 23:54:17', '2026-08-09 21:22:22'),
(2, 'spam fesnuk', 'hujat', 'untuk menghujat di facebook', 'poko punya hp', 1, 'Siti Rahmawati, S.ST, M.T.', '19850722 201001 2 012', 'aktif', '2026-08-09 21:22:18', '2026-08-05 23:55:01', '2026-08-09 21:22:18'),
(3, 'Sekretariat', 'sekretariat', 'Deskripsi posisi setelah perbaikan form submit.', '-', 0, 'Budi Santoso, S.Kom, M.Eng', '19791104 200501 1 008', 'aktif', NULL, '2026-08-09 21:24:01', '2026-08-23 20:10:59'),
(4, 'Komunikasi dan Informasi Publik', 'komunikasi-dan-informasi-publik', 'Media Social Management\r\nContent Creation & Copywriting\r\nPeliputan & Fotografi Berita\r\nLayanan PPID & Informasi Publik', '-', 1, 'Ir. Ahmad Zulkarnain, M.T.', '19810418 200902 1 003', 'aktif', NULL, '2026-08-09 21:24:46', '2026-08-09 21:24:46'),
(5, 'Aplikasi dan Informatika', 'aplikasi-dan-informatika', 'Web & Mobile App Development\r\nUI/UX Design\r\nDatabase & Cloud Server Management\r\nIntegrasi Sistem SPBE', '-', 1, 'Dewi Lestari, S.T., M.Sc.', '19870912 201101 2 009', 'aktif', NULL, '2026-08-09 21:25:19', '2026-08-09 21:25:19'),
(6, 'Statistik', 'statistik', 'Data Collection & Cleaning\r\nData Visualization & Dashboard\r\nAnalisis Data Statistik Sektoral\r\nSatu Data Kabupaten Tuban', '-', 1, 'Drs. Eko Prasetyo, M.Kom', '19820315 200801 1 004', 'aktif', NULL, '2026-08-09 21:25:49', '2026-08-09 21:25:49'),
(7, 'Persandian', 'persandian', 'Cyber Security Monitoring\r\nPengelolaan Sertifikat Elektronik (TTE)\r\nVulnerability Assessment Sederhana\r\nTata Kelola Keamanan Informasi', '-', 1, 'Siti Rahmawati, S.ST, M.T.', '19850722 201001 2 012', 'aktif', '2026-08-23 19:45:12', '2026-08-09 21:26:16', '2026-08-23 19:45:12'),
(8, 'Posisi 1', 'posisi-1', 'Deskripsi 1', NULL, 1, 'Budi Santoso, S.Kom, M.Eng', '19791104 200501 1 008', 'aktif', '2026-08-23 20:04:59', '2026-08-23 20:00:52', '2026-08-23 20:04:59'),
(9, 'Posisi 2', 'posisi-2', 'Deskripsi 2', NULL, 1, 'Ir. Ahmad Zulkarnain, M.T.', '19810418 200902 1 003', 'aktif', '2026-08-23 20:05:50', '2026-08-23 20:00:52', '2026-08-23 20:05:50'),
(10, 'Posisi 3', 'posisi-3', 'Deskripsi 3', NULL, 1, 'Dewi Lestari, S.T., M.Sc.', '19870912 201101 2 009', 'aktif', '2026-08-23 20:05:49', '2026-08-23 20:00:52', '2026-08-23 20:05:49'),
(11, 'Posisi 4', 'posisi-4', 'Deskripsi 4', NULL, 1, 'Drs. Eko Prasetyo, M.Kom', '19820315 200801 1 004', 'aktif', '2026-08-23 20:05:47', '2026-08-23 20:00:52', '2026-08-23 20:05:47'),
(12, 'Posisi 5', 'posisi-5', 'Deskripsi 5', NULL, 1, 'Siti Rahmawati, S.ST, M.T.', '19850722 201001 2 012', 'aktif', '2026-08-23 20:05:46', '2026-08-23 20:00:52', '2026-08-23 20:05:46'),
(13, 'Posisi 6', 'posisi-6', 'Deskripsi 6', NULL, 1, 'Budi Santoso, S.Kom, M.Eng', '19791104 200501 1 008', 'aktif', '2026-08-23 20:05:45', '2026-08-23 20:00:52', '2026-08-23 20:05:45'),
(14, 'Posisi 7', 'posisi-7', 'Deskripsi 7', NULL, 1, 'Ir. Ahmad Zulkarnain, M.T.', '19810418 200902 1 003', 'aktif', '2026-08-23 20:05:43', '2026-08-23 20:00:52', '2026-08-23 20:05:43'),
(15, 'Posisi 8', 'posisi-8', 'Deskripsi 8', NULL, 1, 'Dewi Lestari, S.T., M.Sc.', '19870912 201101 2 009', 'aktif', '2026-08-23 20:05:42', '2026-08-23 20:00:52', '2026-08-23 20:05:42'),
(16, 'Posisi 9', 'posisi-9', 'Deskripsi 9', NULL, 1, 'Drs. Eko Prasetyo, M.Kom', '19820315 200801 1 004', 'aktif', '2026-08-23 20:05:41', '2026-08-23 20:00:52', '2026-08-23 20:05:41'),
(17, 'Posisi 10', 'posisi-10', 'Deskripsi 10', NULL, 1, 'Siti Rahmawati, S.ST, M.T.', '19850722 201001 2 012', 'aktif', '2026-08-23 20:05:39', '2026-08-23 20:00:52', '2026-08-23 20:05:39'),
(18, 'Posisi 11', 'posisi-11', 'Deskripsi 11', NULL, 1, 'Budi Santoso, S.Kom, M.Eng', '19791104 200501 1 008', 'aktif', '2026-08-23 20:05:37', '2026-08-23 20:00:52', '2026-08-23 20:05:37'),
(19, 'Posisi 12', 'posisi-12', 'Deskripsi 12', NULL, 1, 'Ir. Ahmad Zulkarnain, M.T.', '19810418 200902 1 003', 'aktif', '2026-08-23 20:05:35', '2026-08-23 20:00:52', '2026-08-23 20:05:35'),
(20, 'Posisi 13', 'posisi-13', 'Deskripsi 13', NULL, 1, 'Dewi Lestari, S.T., M.Sc.', '19870912 201101 2 009', 'aktif', '2026-08-23 20:05:33', '2026-08-23 20:00:52', '2026-08-23 20:05:33'),
(21, 'Posisi 14', 'posisi-14', 'Deskripsi 14', NULL, 1, 'Drs. Eko Prasetyo, M.Kom', '19820315 200801 1 004', 'aktif', '2026-08-23 20:05:32', '2026-08-23 20:00:52', '2026-08-23 20:05:32'),
(22, 'Posisi 15', 'posisi-15', 'Deskripsi 15', NULL, 1, 'Siti Rahmawati, S.ST, M.T.', '19850722 201001 2 012', 'aktif', '2026-08-23 20:05:30', '2026-08-23 20:00:52', '2026-08-23 20:05:30'),
(23, 'Posisi 1', 'posisi-1', 'Deskripsi 1', NULL, 1, 'Budi Santoso, S.Kom, M.Eng', '19791104 200501 1 008', 'aktif', '2026-08-23 20:05:27', '2026-08-23 20:01:08', '2026-08-23 20:05:27'),
(24, 'Posisi 2', 'posisi-2', 'Deskripsi 2', NULL, 1, 'Ir. Ahmad Zulkarnain, M.T.', '19810418 200902 1 003', 'aktif', '2026-08-23 20:05:26', '2026-08-23 20:01:08', '2026-08-23 20:05:26'),
(25, 'Posisi 3', 'posisi-3', 'Deskripsi 3', NULL, 1, 'Dewi Lestari, S.T., M.Sc.', '19870912 201101 2 009', 'aktif', '2026-08-23 20:05:25', '2026-08-23 20:01:08', '2026-08-23 20:05:25'),
(26, 'Posisi 4', 'posisi-4', 'Deskripsi 4', NULL, 1, 'Drs. Eko Prasetyo, M.Kom', '19820315 200801 1 004', 'aktif', '2026-08-23 20:05:22', '2026-08-23 20:01:08', '2026-08-23 20:05:22'),
(27, 'Posisi 5', 'posisi-5', 'Deskripsi 5', NULL, 1, 'Siti Rahmawati, S.ST, M.T.', '19850722 201001 2 012', 'aktif', '2026-08-23 20:05:21', '2026-08-23 20:01:08', '2026-08-23 20:05:21'),
(28, 'Posisi 6', 'posisi-6', 'Deskripsi 6', NULL, 1, 'Budi Santoso, S.Kom, M.Eng', '19791104 200501 1 008', 'aktif', '2026-08-23 20:05:19', '2026-08-23 20:01:08', '2026-08-23 20:05:19'),
(29, 'Posisi 7', 'posisi-7', 'Deskripsi 7', NULL, 1, 'Ir. Ahmad Zulkarnain, M.T.', '19810418 200902 1 003', 'aktif', '2026-08-23 20:05:17', '2026-08-23 20:01:08', '2026-08-23 20:05:17'),
(30, 'Posisi 8', 'posisi-8', 'Deskripsi 8', NULL, 1, 'Dewi Lestari, S.T., M.Sc.', '19870912 201101 2 009', 'aktif', '2026-08-23 20:05:15', '2026-08-23 20:01:08', '2026-08-23 20:05:15'),
(31, 'Posisi 9', 'posisi-9', 'Deskripsi 9', NULL, 1, 'Drs. Eko Prasetyo, M.Kom', '19820315 200801 1 004', 'aktif', '2026-08-23 20:05:13', '2026-08-23 20:01:08', '2026-08-23 20:05:13'),
(32, 'Posisi 10', 'posisi-10', 'Deskripsi 10', NULL, 1, 'Siti Rahmawati, S.ST, M.T.', '19850722 201001 2 012', 'aktif', '2026-08-23 20:05:12', '2026-08-23 20:01:08', '2026-08-23 20:05:12'),
(33, 'Posisi 11', 'posisi-11', 'Deskripsi 11', NULL, 1, 'Budi Santoso, S.Kom, M.Eng', '19791104 200501 1 008', 'aktif', '2026-08-23 20:05:10', '2026-08-23 20:01:08', '2026-08-23 20:05:10'),
(34, 'Posisi 12', 'posisi-12', 'Deskripsi 12', NULL, 1, 'Ir. Ahmad Zulkarnain, M.T.', '19810418 200902 1 003', 'aktif', '2026-08-23 20:05:09', '2026-08-23 20:01:08', '2026-08-23 20:05:09'),
(35, 'Posisi 13', 'posisi-13', 'Deskripsi 13', NULL, 1, 'Dewi Lestari, S.T., M.Sc.', '19870912 201101 2 009', 'aktif', '2026-08-23 20:05:07', '2026-08-23 20:01:08', '2026-08-23 20:05:07'),
(36, 'Posisi 14', 'posisi-14', 'Deskripsi 14', NULL, 1, 'Drs. Eko Prasetyo, M.Kom', '19820315 200801 1 004', 'aktif', '2026-08-23 20:05:05', '2026-08-23 20:01:08', '2026-08-23 20:05:05'),
(37, 'Posisi 15', 'posisi-15', 'Deskripsi 15', NULL, 1, 'Siti Rahmawati, S.ST, M.T.', '19850722 201001 2 012', 'aktif', '2026-08-23 20:04:26', '2026-08-23 20:01:08', '2026-08-23 20:04:26'),
(38, 'Persandian', 'persandian', 'Enkripsi Data, Keamanan Jaringan, Pengamanan Informasi, Audit & Evaluasi Sistem, Alat Sandi.', NULL, 0, 'Budi Santoso, S.Kom, M.Eng', '19791104 200501 1 008', 'aktif', '2026-08-23 20:05:02', '2026-08-23 20:04:07', '2026-08-23 20:05:02'),
(39, 'Persandian', 'persandian', 'oke', NULL, 0, 'Ir. Ahmad Zulkarnain, M.T.', '19810418 200902 1 003', 'aktif', NULL, '2026-08-23 20:06:18', '2026-08-23 20:39:31'),
(40, 'scroll tt', 's', 'fgh', 'cvb', 0, NULL, NULL, 'aktif', '2026-09-08 07:19:47', '2026-09-03 07:39:01', '2026-09-08 07:19:47'),
(41, 'scroll fesnuk', 'scroll-fesnuk', 'okroke', 'oke', 0, 'bagus', '1234567890', 'aktif', '2026-09-08 07:19:43', '2026-09-04 02:11:59', '2026-09-08 07:19:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `profiles`
--

CREATE TABLE `profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `participant_type` varchar(20) NOT NULL DEFAULT 'mahasiswa',
  `nik` varchar(16) DEFAULT NULL,
  `nama_lengkap` varchar(150) DEFAULT NULL,
  `nis_nim` varchar(50) DEFAULT NULL,
  `nim` varchar(30) DEFAULT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` varchar(15) NOT NULL,
  `alamat` text NOT NULL,
  `no_telepon` varchar(20) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `institusi` varchar(200) NOT NULL,
  `jurusan` varchar(150) NOT NULL,
  `tahun_angkatan` varchar(10) NOT NULL,
  `semester` tinyint(3) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `profiles`
--

INSERT INTO `profiles` (`id`, `user_id`, `participant_type`, `nik`, `nama_lengkap`, `nis_nim`, `nim`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `alamat`, `no_telepon`, `foto`, `institusi`, `jurusan`, `tahun_angkatan`, `semester`, `created_at`, `updated_at`) VALUES
(3, 21, 'student', '3523143010060001', 'Blackrose', '24050974004', NULL, 'bojonegoro', '2007-06-13', 'Laki-laki', 'Dsn. Beron Rt04/rt05, Ds. Punggulrejo, Kec. rengel, Kab. tuban.', '082329267649', 'profiles/profile_21_20260921084155_097fe977.png', 'SMA Negeri 1 Rengel', 'Rekayasa perangkat Lunak', '2026', NULL, '2026-08-09 20:13:43', '2026-09-21 01:41:55'),
(9, 24, 'student', '1234567898765432', 'Bagus Nih', '123234', NULL, 'Bojonegoro', '2006-01-01', 'Laki-laki', 'oke poko de[ konter ada sumurnya ada tokonya oke baik', '082329267650', 'profiles/profile_24_20260811075812_5dd0d9f5.png', 'SMKN 1 Tuban', 'TKJ', '2026', NULL, '2026-08-10 20:01:54', '2026-08-11 00:58:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `registrations`
--

CREATE TABLE `registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nomor_pendaftaran` varchar(50) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `position_id` bigint(20) UNSIGNED NOT NULL,
  `cv_path` varchar(255) NOT NULL,
  `surat_pengantar_path` varchar(255) NOT NULL,
  `proposal_magang_path` varchar(255) DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'submitted',
  `catatan_admin` text DEFAULT NULL,
  `surat_balasan_path` varchar(255) DEFAULT NULL,
  `is_terminated` tinyint(1) NOT NULL DEFAULT 0,
  `catatan_penonaktifan` text DEFAULT NULL,
  `terminated_at` datetime DEFAULT NULL,
  `tanggal_submit` datetime NOT NULL DEFAULT current_timestamp(),
  `periode_mulai` date DEFAULT NULL,
  `periode_selesai` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `registrations`
--

INSERT INTO `registrations` (`id`, `nomor_pendaftaran`, `user_id`, `position_id`, `cv_path`, `surat_pengantar_path`, `proposal_magang_path`, `status`, `catatan_admin`, `surat_balasan_path`, `is_terminated`, `catatan_penonaktifan`, `terminated_at`, `tanggal_submit`, `periode_mulai`, `periode_selesai`, `created_at`, `updated_at`) VALUES
(23, 'MAGANG-2026-0014', 21, 5, 'registrations/202609/cv_202609_user21_20260909103800_55e1eaa2.pdf', 'registrations/202609/surat_pengantar_202609_user21_20260909103800_4e14a036.pdf', 'registrations/202609/proposal_magang_202609_user21_20260909103800_46e3ef6d.pdf', 'rejected', 'oke kamu gagal', NULL, 0, NULL, NULL, '2026-09-09 10:38:00', '2026-09-11', '2026-12-18', '2026-09-09 03:38:00', '2026-09-09 03:41:22'),
(24, 'MAGANG-2026-0015', 24, 5, 'registrations/202609/cv_202609_user24_20260910111255_b50d1973.pdf', 'registrations/202609/surat_pengantar_202609_user24_20260910111255_0d8eb566.pdf', 'registrations/202609/proposal_magang_202609_user24_20260910111255_a7914fdf.pdf', 'accepted', 'okedeh', 'surat_balasan/SURAT-BALASAN-MAGANG-2026-0015-20260910114224-8c39d1ca.pdf', 0, NULL, NULL, '2026-09-10 11:12:55', '2026-09-12', '2026-12-26', '2026-09-10 04:12:55', '2026-09-10 04:42:24'),
(25, 'MAGANG-2026-0016', 21, 6, 'registrations/202609/cv_202609_user21_20260921084524_fa08da42.pdf', 'registrations/202609/surat_pengantar_202609_user21_20260921084524_bdff4c66.pdf', 'registrations/202609/proposal_magang_202609_user21_20260921084524_ed030037.pdf', 'accepted', 'oke done', 'surat_balasan/SURAT-BALASAN-MAGANG-2026-0016-20260921085125-5c129a94.pdf', 0, NULL, NULL, '2026-09-21 08:45:24', '2026-09-22', '2026-11-22', '2026-09-21 01:45:24', '2026-09-21 01:51:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('6jOtjxs6KNGBIILsbQgHAblSMtmnZJNobvGmwCXe', 22, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJZZjBsNmVpd1lLYVdBMFg5U0Nkanh2MWMzQk5NSVRXQ1VVODB3eElQIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC9kYXNoYm9hcmQiLCJyb3V0ZSI6ImFkbWluLmRhc2hib2FyZCJ9LCJ1cmwiOltdLCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MjJ9', 1789956662);

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'text',
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `type`, `group`, `created_at`, `updated_at`) VALUES
(1, 'site_title', 'SIMAGANG — Dinas Komunikasi dan Informatika, Statistik dan Persandian Kabupaten Tuban', 'text', 'global', '2026-09-12 14:10:41', '2026-09-13 12:46:58'),
(2, 'app_name', 'SIMAGANG', 'text', 'global', '2026-09-12 14:10:41', '2026-09-13 13:17:42'),
(3, 'institution_name', 'Diskominfo SP Kab. Tuban', 'text', 'global', '2026-09-12 14:10:41', '2026-09-12 14:10:41'),
(4, 'site_logo', 'traveland/images/logo.png', 'image', 'global', '2026-09-12 14:10:41', '2026-09-12 14:10:41'),
(5, 'meta_description', 'Portal Tidak Resmi Pendaftaran Magang Diskominfo SP Kab. Tuban. Daftarkan dirimu secara digital!', 'text', 'global', '2026-09-12 14:10:41', '2026-09-13 12:46:58'),
(6, 'hero_badge', 'Portal Resmi Pendaftaran Magang', 'text', 'landing_page', '2026-09-12 14:10:42', '2026-09-12 14:10:42'),
(7, 'hero_title', 'Membangun Talenta Digital untuk Pelayanan Publik', 'text', 'landing_page', '2026-09-12 14:10:42', '2026-09-12 14:10:42'),
(8, 'hero_description', 'SIMAGANG (Sistem Informasi Magang) merupakan portal resmi Diskominfo SP Kabupaten Tuban untuk memfasilitasi pendaftaran dan pengelolaan magang secara digital. Dapatkan pengalaman kerja nyata dan kembangkan kompetensimu melalui proses rekrutmen yang transparan, terintegrasi, dan 100% paperless.', 'text', 'landing_page', '2026-09-12 14:10:42', '2026-09-12 14:10:42'),
(9, 'hero_image', 'storage/settings/5aLKEx1NrVQOCUJi1dMT5wOZQDIn6udFDxf4Ogdv.png', 'image', 'landing_page', '2026-09-12 14:10:42', '2026-09-17 02:54:37'),
(10, 'about_title', 'SIMAGANG Diskominfo SP', 'text', 'landing_page', '2026-09-12 14:10:42', '2026-09-12 14:10:42'),
(11, 'about_description', 'Platform pendaftaran magang resmi untuk Mahasiswa dan Siswa SMK. Seluruh proses dilakukan 100% secara digital, terstruktur, dan transparan.', 'text', 'landing_page', '2026-09-12 14:10:42', '2026-09-12 14:10:42'),
(12, 'about_image', 'traveland/images/2.png', 'image', 'landing_page', '2026-09-12 14:10:42', '2026-09-12 14:10:42'),
(13, 'contact_address', 'Jl. Mastrip No. 5 A, Sidorejo, Kec. Tuban, Jawa Timur 62315', 'text', 'contact', '2026-09-12 14:10:42', '2026-09-12 14:10:42'),
(14, 'contact_email', 'diskominfo@tubankab.go.id', 'text', 'contact', '2026-09-12 14:10:42', '2026-09-12 14:10:42'),
(15, 'contact_phone', '(0356) 8832697', 'text', 'contact', '2026-09-12 14:10:42', '2026-09-12 14:10:42'),
(16, 'contact_working_hours', 'Senin - Jum\'at: 07.30 - 16.00 WIB', 'text', 'contact', '2026-09-12 14:10:42', '2026-09-12 14:10:42'),
(17, 'social_website', 'https://diskominfo.tubankab.go.id', 'url', 'contact', '2026-09-12 14:10:42', '2026-09-12 14:10:42'),
(18, 'social_facebook', 'https://www.facebook.com/diskominfo.tuban', 'url', 'contact', '2026-09-12 14:10:42', '2026-09-12 14:10:42'),
(19, 'social_instagram', 'https://www.instagram.com/kominfo.tuban', 'url', 'contact', '2026-09-12 14:10:42', '2026-09-12 14:10:42'),
(20, 'social_twitter', 'https://twitter.com/DiskominfoTuban', 'url', 'contact', '2026-09-12 14:10:42', '2026-09-12 14:10:42'),
(21, 'social_youtube', 'https://www.youtube.com/channel/UC7V9cxzD7Gk-K_jxGMbblgA?view_as=subscriber', 'url', 'contact', '2026-09-12 14:10:42', '2026-09-12 14:10:42'),
(22, 'maps_embed_url', 'https://www.google.com/maps/embed?pb=!4v1788316632382!6m8!1m7!1szab-FoOpFkmJVJ79X0G0Pw!2m2!1d-6.901873934235668!2d112.0440727763729!3f117.32336345271811!4f-6.10453670657121!5f0.4000000000000002', 'url', 'contact', '2026-09-12 14:10:42', '2026-09-12 14:10:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `surveys`
--

CREATE TABLE `surveys` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `komentar` text DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `surveys`
--

INSERT INTO `surveys` (`id`, `rating`, `komentar`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 5, 'Layanan magang digital SIM-MAGANG sangat responsif dan transparan!', '127.0.0.1', '2026-09-01 02:40:25', '2026-09-01 02:40:25'),
(2, 4, 'Proses seleksi dan informasi posisi magang sangat jelas.', '192.168.1.10', '2026-09-01 02:40:25', '2026-09-01 02:40:25'),
(3, 5, 'Sistem yang sangat memudahkan pendaftaran magang di Diskominfo Tuban.', '180.252.12.44', '2026-09-01 02:40:25', '2026-09-01 02:40:25'),
(4, 3, 'Mohon ditambah pilihan posisi magang untuk jurusan desain visual.', '36.78.20.15', '2026-09-01 02:40:25', '2026-09-01 02:40:25'),
(5, 5, 'oke baik', '127.0.0.1', '2026-09-01 03:26:21', '2026-09-01 03:26:21'),
(6, 5, 'oke', '127.0.0.1', '2026-09-03 07:34:06', '2026-09-03 07:34:06'),
(7, 3, NULL, '127.0.0.1', '2026-09-08 04:22:04', '2026-09-08 04:22:04'),
(8, 5, 'baik bgt', '127.0.0.1', '2026-09-09 06:26:33', '2026-09-09 06:26:33'),
(9, 5, NULL, '127.0.0.1', '2026-09-17 04:31:20', '2026-09-17 04:31:20'),
(10, 5, NULL, '127.0.0.1', '2026-09-17 04:31:40', '2026-09-17 04:31:40'),
(11, 5, NULL, '127.0.0.1', '2026-09-17 04:37:14', '2026-09-17 04:37:14'),
(12, 4, 'oke baik terimaksih', '127.0.0.1', '2026-09-17 04:37:33', '2026-09-17 04:37:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nip` varchar(30) DEFAULT NULL,
  `position_title` varchar(100) DEFAULT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'peserta',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `nip`, `position_title`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(21, 'bagus dwi', 'bagusdwijunior@gmail.com', NULL, NULL, 'peserta', NULL, '$2y$12$lAd8Ha/xKmKZ42V915pd/eRg18MaiRH/Ipm5GNbZDc8Ouq5874c..', '19C2N9SIfuNJg1bCS4MLWafxEKsOXHuvDRdfygjMAN9Jh3tdEkJo7qWcxEYi', '2026-08-05 22:23:05', '2026-08-26 06:10:19'),
(22, 'Administrator Diskominfo Tuban', 'diskominfo@tubankab.go.id', '19820315 200801 1 004', 'Kepala Bidang Aplikasi & Informatika Diskominfo Tuban', 'admin', NULL, '$2y$12$VuWRgWkaonclWYYYfy1bF.4ORlQepRege6MVtM4YmLavYar/5umL6', 'NgOpU5f9xHoKtuMjMd4WHG2HsyAu1563bBNeO0XnGhFugXLLu5n7I9QBJlC1', '2026-08-05 22:28:35', '2026-09-04 06:59:23'),
(24, 'bagus nih', '24050974004@mhs.unesa.ac.id', NULL, NULL, 'peserta', NULL, '$2y$12$cEz9StkJ.m70HDaHlajOaOx4gquXSW3I9jtCR49UqQq/Dg6.LDgNS', 'ujrezTnMBk0u2TM0yiQrWR3DvM5n13mCjSAz3dUbm4rbMhAliYIRI77yaVYj', '2026-08-09 19:45:54', '2026-08-13 20:29:55');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `landing_contents`
--
ALTER TABLE `landing_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `landing_contents_section_is_active_order_index` (`section`,`is_active`,`order`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `positions_status_index` (`status`),
  ADD KEY `positions_nama_posisi_index` (`nama_posisi`),
  ADD KEY `positions_slug_index` (`slug`);

--
-- Indeks untuk tabel `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `profiles_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `profiles_nis_nim_unique` (`nis_nim`),
  ADD UNIQUE KEY `profiles_nik_unique` (`nik`),
  ADD UNIQUE KEY `profiles_nim_unique` (`nim`),
  ADD KEY `profiles_institusi_index` (`institusi`),
  ADD KEY `profiles_jurusan_index` (`jurusan`),
  ADD KEY `profiles_nik_nama_lengkap_index` (`nik`,`nama_lengkap`),
  ADD KEY `profiles_participant_type_index` (`participant_type`);

--
-- Indeks untuk tabel `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `registrations_nomor_pendaftaran_unique` (`nomor_pendaftaran`),
  ADD KEY `registrations_user_id_index` (`user_id`),
  ADD KEY `registrations_position_id_index` (`position_id`),
  ADD KEY `registrations_position_id_status_index` (`position_id`,`status`),
  ADD KEY `registrations_tanggal_submit_index` (`tanggal_submit`),
  ADD KEY `registrations_status_index` (`status`),
  ADD KEY `registrations_periode_mulai_periode_selesai_index` (`periode_mulai`,`periode_selesai`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indeks untuk tabel `surveys`
--
ALTER TABLE `surveys`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_nip_unique` (`nip`),
  ADD KEY `users_role_index` (`role`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT untuk tabel `landing_contents`
--
ALTER TABLE `landing_contents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `positions`
--
ALTER TABLE `positions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT untuk tabel `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `surveys`
--
ALTER TABLE `surveys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `registrations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
