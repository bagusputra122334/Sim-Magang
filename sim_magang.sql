-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 07 Sep 2026 pada 03.23
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
('sistem-informasi-magang-cache-5c785c036466adea360111aa28563bfd556b5fba', 'i:2;', 1788505849),
('sistem-informasi-magang-cache-5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1788505849;', 1788505849);

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
(53, 'fd4ac42d-403e-4773-a10e-440c953135a8', 'database', 'default', '{\"uuid\":\"fd4ac42d-403e-4773-a10e-440c953135a8\",\"displayName\":\"App\\\\Notifications\\\\QueuedUserResetPassword\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:22;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:41:\\\"App\\\\Notifications\\\\QueuedUserResetPassword\\\":2:{s:5:\\\"token\\\";s:64:\\\"6ba9690a6b80a81d5edb35780de98426c3ea295366e9962df2e455a0f4da4e6d\\\";s:2:\\\"id\\\";s:36:\\\"9c29f5ad-b5db-4179-8210-59bbc5028728\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\",\"batchId\":null},\"createdAt\":1788505304,\"delay\":null}', 'Resend\\Exceptions\\ErrorException: You can only send testing emails to your own email address (bagusdwijunior@gmail.com). To send emails to other recipients, please verify a domain at resend.com/domains, and change the `from` address to an email using this domain. in C:\\xampp\\htdocs\\simang\\vendor\\resend\\resend-php\\src\\Transporters\\HttpTransporter.php:120\nStack trace:\n#0 C:\\xampp\\htdocs\\simang\\vendor\\resend\\resend-php\\src\\Transporters\\HttpTransporter.php(47): Resend\\Transporters\\HttpTransporter->throwIfJsonError(Array, \'{\"statusCode\":4...\')\n#1 C:\\xampp\\htdocs\\simang\\vendor\\resend\\resend-php\\src\\Service\\Email.php(50): Resend\\Transporters\\HttpTransporter->request(Object(Resend\\ValueObjects\\Transporter\\Payload))\n#2 C:\\xampp\\htdocs\\simang\\vendor\\resend\\resend-php\\src\\Service\\Email.php(62): Resend\\Service\\Email->create(Array, Array)\n#3 C:\\xampp\\htdocs\\simang\\vendor\\resend\\resend-laravel\\src\\Transport\\ResendTransportFactory.php(60): Resend\\Service\\Email->send(Array, Array)\n#4 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\mailer\\Transport\\AbstractTransport.php(69): Resend\\Laravel\\Transport\\ResendTransportFactory->doSend(Object(Symfony\\Component\\Mailer\\SentMessage))\n#5 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(588): Symfony\\Component\\Mailer\\Transport\\AbstractTransport->send(Object(Symfony\\Component\\Mime\\Email), Object(Symfony\\Component\\Mailer\\DelayedEnvelope))\n#6 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(331): Illuminate\\Mail\\Mailer->sendSymfonyMessage(Object(Symfony\\Component\\Mime\\Email))\n#7 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\MailChannel.php(66): Illuminate\\Mail\\Mailer->send(Object(Closure), Array, Object(Closure))\n#8 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(165): Illuminate\\Notifications\\Channels\\MailChannel->send(Object(App\\Models\\User), Object(App\\Notifications\\QueuedUserResetPassword))\n#9 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(120): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\User), \'5f1f261e-40d0-4...\', Object(App\\Notifications\\QueuedUserResetPassword), \'mail\')\n#10 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(21): Illuminate\\Notifications\\NotificationSender->{closure:Illuminate\\Notifications\\NotificationSender::sendNow():115}()\n#11 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(115): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#12 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(61): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\QueuedUserResetPassword), Array)\n#13 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(130): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\QueuedUserResetPassword), Array)\n#14 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#15 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::{closure:Illuminate\\Container\\BoundMethod::call():35}()\n#16 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#17 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#18 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(800): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#19 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(136): Illuminate\\Container\\Container->call(Array)\n#20 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Bus\\Dispatcher->{closure:Illuminate\\Bus\\Dispatcher::dispatchNow():133}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#21 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->{closure:Illuminate\\Pipeline\\Pipeline::prepareDestination():178}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#22 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(140): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#23 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(155): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#24 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->{closure:Illuminate\\Queue\\CallQueuedHandler::dispatchThroughMiddleware():148}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#25 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->{closure:Illuminate\\Pipeline\\Pipeline::prepareDestination():178}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#26 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(148): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#27 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(86): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#28 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#29 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(559): Illuminate\\Queue\\Jobs\\Job->fire()\n#30 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(505): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#31 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(257): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#32 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#33 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#34 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#35 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::{closure:Illuminate\\Container\\BoundMethod::call():35}()\n#36 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#37 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#38 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(800): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#39 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(292): Illuminate\\Container\\Container->call(Array)\n#40 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Command\\Command.php(284): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#41 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(261): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Application.php(1144): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Application.php(379): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Application.php(218): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1242): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\xampp\\htdocs\\simang\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#48 {main}\n\nNext Symfony\\Component\\Mailer\\Exception\\TransportException: Request to the Resend API failed. Reason: You can only send testing emails to your own email address (bagusdwijunior@gmail.com). To send emails to other recipients, please verify a domain at resend.com/domains, and change the `from` address to an email using this domain. in C:\\xampp\\htdocs\\simang\\vendor\\resend\\resend-laravel\\src\\Transport\\ResendTransportFactory.php:74\nStack trace:\n#0 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\mailer\\Transport\\AbstractTransport.php(69): Resend\\Laravel\\Transport\\ResendTransportFactory->doSend(Object(Symfony\\Component\\Mailer\\SentMessage))\n#1 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(588): Symfony\\Component\\Mailer\\Transport\\AbstractTransport->send(Object(Symfony\\Component\\Mime\\Email), Object(Symfony\\Component\\Mailer\\DelayedEnvelope))\n#2 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(331): Illuminate\\Mail\\Mailer->sendSymfonyMessage(Object(Symfony\\Component\\Mime\\Email))\n#3 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\MailChannel.php(66): Illuminate\\Mail\\Mailer->send(Object(Closure), Array, Object(Closure))\n#4 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(165): Illuminate\\Notifications\\Channels\\MailChannel->send(Object(App\\Models\\User), Object(App\\Notifications\\QueuedUserResetPassword))\n#5 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(120): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\User), \'5f1f261e-40d0-4...\', Object(App\\Notifications\\QueuedUserResetPassword), \'mail\')\n#6 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(21): Illuminate\\Notifications\\NotificationSender->{closure:Illuminate\\Notifications\\NotificationSender::sendNow():115}()\n#7 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(115): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#8 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(61): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\QueuedUserResetPassword), Array)\n#9 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(130): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\QueuedUserResetPassword), Array)\n#10 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#11 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::{closure:Illuminate\\Container\\BoundMethod::call():35}()\n#12 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#13 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#14 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(800): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#15 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(136): Illuminate\\Container\\Container->call(Array)\n#16 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Bus\\Dispatcher->{closure:Illuminate\\Bus\\Dispatcher::dispatchNow():133}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#17 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->{closure:Illuminate\\Pipeline\\Pipeline::prepareDestination():178}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#18 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(140): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#19 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(155): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#20 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->{closure:Illuminate\\Queue\\CallQueuedHandler::dispatchThroughMiddleware():148}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#21 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->{closure:Illuminate\\Pipeline\\Pipeline::prepareDestination():178}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#22 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(148): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#23 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(86): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#24 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#25 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(559): Illuminate\\Queue\\Jobs\\Job->fire()\n#26 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(505): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#27 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(257): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#28 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#29 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#30 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#31 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::{closure:Illuminate\\Container\\BoundMethod::call():35}()\n#32 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#33 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#34 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(800): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#35 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(292): Illuminate\\Container\\Container->call(Array)\n#36 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Command\\Command.php(284): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#37 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(261): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#38 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Application.php(1144): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#39 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Application.php(379): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#40 C:\\xampp\\htdocs\\simang\\vendor\\symfony\\console\\Application.php(218): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#41 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#42 C:\\xampp\\htdocs\\simang\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1242): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 C:\\xampp\\htdocs\\simang\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#44 {main}', '2026-09-04 07:05:04');

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
(18, '2026_09_01_000000_create_surveys_table', 9);

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
(40, 'scroll tt', 's', 'fgh', 'cvb', 0, NULL, NULL, 'aktif', NULL, '2026-09-03 07:39:01', '2026-09-03 07:39:01'),
(41, 'scroll fesnuk', 'scroll-fesnuk', 'okroke', 'oke', 0, 'bagus', '1234567890', 'aktif', NULL, '2026-09-04 02:11:59', '2026-09-04 02:11:59');

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
(2, 23, 'mahasiswa', '2345678909876212', 'putra', '123456789123', '123456789123', 'bawean', '2007-02-06', 'Perempuan', 'tegalbag jualan tegal', '082329267650', 'profiles/profile_23_20260806070238_a706b2ca.png', 'smkn11 bawean', 'teknik hujat media', '2027', 7, '2026-08-06 00:02:24', '2026-08-06 00:02:38'),
(3, 21, 'university', '3523143010060001', 'Blackrose', '24050974004', '24050974004', 'bojonegoro', '2007-06-13', 'Laki-laki', 'Dsn. Beron Rt04/rt05, Ds. Punggulrejo, Kec. rengel, Kab. tuban.', '082329267649', 'profiles/profile_21_20260810031343_74cb0676.png', 'Universitas Negeri Surabaya', 'S1 Pendidikan Teknologi Informasi', '2026', 1, '2026-08-09 20:13:43', '2026-08-09 21:05:26'),
(4, 25, 'university', '1234567890987543', 'Atlantis', '12233445566', '12233445566', 'surabaya', '2006-09-11', 'Perempuan', 'depan perempatan', '082329267650', NULL, 'universitas Padjajaran', 'Teknik Informatika', '2026', 1, '2026-08-09 23:33:03', '2026-08-09 23:33:03'),
(9, 24, 'student', '1234567898765432', 'Bagus Nih', '123234', NULL, 'Bojonegoro', '2006-01-01', 'Laki-laki', 'oke poko de[ konter ada sumurnya ada tokonya oke baik', '082329267650', 'profiles/profile_24_20260811075812_5dd0d9f5.png', 'SMKN 1 Tuban', 'TKJ', '2026', NULL, '2026-08-10 20:01:54', '2026-08-11 00:58:12'),
(11, 33, 'university', '1232345445566776', 'DPM FT UNESA', '22040974003', '22040974003', 'unesa', '2006-05-15', 'Laki-laki', 'jalan jalan dengan spatu rodaku', '082312344321', NULL, 'unesa', 'Teknik Informatika', '2024', 4, '2026-08-24 07:16:33', '2026-08-24 07:16:33');

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
(2, 'MAGANG-2026-0002', 23, 3, 'registrations/202608/cv_202608_user23_20260806070319_9b833bed.pdf', 'registrations/202608/surat_pengantar_202608_user23_20260806070319_619e3de9.pdf', NULL, 'accepted', 'karena di riwayat kamu inter ngehujat kamu diterima', 'surat_balasan/SURAT-BALASAN-MAGANG-2026-0002-20260806070607-b2442bd6.pdf', 0, NULL, NULL, '2026-08-06 07:03:19', '2026-08-08', '2026-12-02', '2026-08-06 00:03:19', '2026-08-11 00:40:37'),
(4, 'MAGANG-2026-0003', 21, 4, 'registrations/202608/cv_202608_user21_20260810031507_c155b98d.pdf', 'registrations/202608/surat_pengantar_202608_user21_20260810031507_831b209a.pdf', 'registrations/202608/proposal_magang_202608_user21_20260810031507_255f28bb.pdf', 'accepted', NULL, NULL, 1, 'Nonaktif manual oleh Admin', '2026-08-26 13:00:59', '2026-08-10 03:15:07', '2026-08-25', '2026-12-15', '2026-08-09 20:15:07', '2026-08-26 06:00:59'),
(5, 'MAGANG-2026-0004', 25, 6, 'registrations/202608/cv_202608_user25_20260810063452_3abe0ce9.pdf', 'registrations/202608/surat_pengantar_202608_user25_20260810063452_ac1c978f.pdf', 'registrations/202608/proposal_magang_202608_user25_20260810063452_2df72b1c.pdf', 'rejected', 'testing uji coba', NULL, 0, NULL, NULL, '2026-08-10 06:34:52', '2026-08-16', '2026-11-30', '2026-08-09 23:34:52', '2026-08-10 00:56:48'),
(6, 'MAGANG-2026-0005', 25, 6, 'registrations/202608/cv_202608_user25_20260810075824_f307fbbf.pdf', 'registrations/202608/surat_pengantar_202608_user25_20260810075824_23c4483a.pdf', 'registrations/202608/proposal_magang_202608_user25_20260810075824_11db06fa.pdf', 'accepted', ',', 'surat_balasan/SURAT-BALASAN-MAGANG-2026-0005-20260810075958-cfb57200.pdf', 0, NULL, NULL, '2026-08-10 07:58:24', '2026-08-30', '2026-11-24', '2026-08-10 00:58:24', '2026-08-10 00:59:58'),
(9, 'MAGANG-2026-0006', 24, 5, 'registrations/202608/cv_202608_user24_20260811055705_8f77f141.pdf', 'registrations/202608/surat_pengantar_202608_user24_20260811055705_6a754487.pdf', 'registrations/202608/proposal_magang_202608_user24_20260811055705_e57e04ef.pdf', 'accepted', 'uio', 'surat_balasan/SURAT-BALASAN-MAGANG-2026-0006-20260811080114-e576279a.pdf', 1, 'Nonaktif manual oleh Admin', '2026-08-24 14:54:42', '2026-08-11 05:57:05', '2026-08-20', '2026-12-21', '2026-08-10 22:57:05', '2026-08-24 07:54:42'),
(11, 'MAGANG-2026-0007', 33, 7, 'registrations/202608/cv_202608_user33_20260813042332_5541754d.pdf', 'registrations/202608/surat_pengantar_202608_user33_20260813042332_d768efca.pdf', 'registrations/202608/proposal_magang_202608_user33_20260813042332_c2d7db8b.pdf', 'accepted', NULL, NULL, 1, 'mengndurkan diri', '2026-08-24 14:10:26', '2026-08-13 04:23:32', '2026-08-26', '2026-12-01', '2026-08-12 21:23:32', '2026-08-24 07:10:26'),
(12, 'MAGANG-TEST-0001', 34, 1, 'cv/old.pdf', 'surat/old.pdf', NULL, 'accepted', NULL, NULL, 1, 'Mengundurkan diri', '2026-08-24 14:19:57', '2026-08-24 14:19:57', '2026-07-24', '2026-10-24', '2026-08-24 07:19:57', '2026-08-24 07:19:57'),
(13, 'MAGANG-2026-0008', 33, 39, 'registrations/202608/cv_202608_user33_20260824142439_45eed181.pdf', 'registrations/202608/surat_pengantar_202608_user33_20260824142439_3720ebf4.pdf', 'registrations/202608/proposal_magang_202608_user33_20260824142439_671aa741.pdf', 'accepted', NULL, 'surat_balasan/SURAT-BALASAN-MAGANG-2026-0008-20260826113023-a56745b7.pdf', 1, 'Nonaktif manual oleh Admin', '2026-08-26 11:34:05', '2026-08-24 14:24:39', '2026-08-25', '2026-11-25', '2026-08-24 07:24:39', '2026-08-26 04:34:05'),
(15, 'MAGANG-2026-0009', 24, 4, 'registrations/202608/cv_202608_user24_20260826100352_fd2486f6.pdf', 'registrations/202608/surat_pengantar_202608_user24_20260826100352_b542f891.pdf', 'registrations/202608/proposal_magang_202608_user24_20260826100352_059acc7b.pdf', 'accepted', NULL, 'surat_balasan/SURAT-BALASAN-MAGANG-2026-0009-20260826112602-83375361.pdf', 0, NULL, NULL, '2026-08-26 10:03:52', '2026-08-27', '2026-12-27', '2026-08-26 03:03:52', '2026-08-26 04:26:02'),
(17, 'MAGANG-2026-0010', 33, 5, 'registrations/202608/cv_202608_user33_20260826120509_61bc7fc6.pdf', 'registrations/202608/surat_pengantar_202608_user33_20260826120509_134978bc.pdf', 'registrations/202608/proposal_magang_202608_user33_20260826120509_742f3e42.pdf', 'submitted', NULL, NULL, 0, NULL, NULL, '2026-08-26 12:05:09', '2026-08-27', '2026-11-27', '2026-08-26 05:05:09', '2026-08-26 05:05:09'),
(20, 'MAGANG-2026-0011', 21, 39, 'registrations/202608/cv_202608_user21_20260826131822_3ff94598.pdf', 'registrations/202608/surat_pengantar_202608_user21_20260826131822_55712d79.pdf', 'registrations/202608/proposal_magang_202608_user21_20260826131822_0b8d9714.pdf', 'accepted', NULL, 'surat_balasan/SURAT-BALASAN-MAGANG-2026-0011-20260826132131-fad3eb00.pdf', 1, 'Nonaktif manual oleh Admin', '2026-08-26 13:34:29', '2026-08-26 13:18:22', '2026-08-28', '2026-12-18', '2026-08-26 06:18:22', '2026-08-26 06:34:29'),
(21, 'MAGANG-2026-0012', 21, 39, 'registrations/202608/cv_202608_user21_20260827081433_af864a9e.pdf', 'registrations/202608/surat_pengantar_202608_user21_20260827081433_161c32a9.pdf', 'registrations/202608/proposal_magang_202608_user21_20260827081433_0048044d.pdf', 'accepted', NULL, 'surat_balasan/SURAT-BALASAN-MAGANG-2026-0012-20260827081709-4c7f1ffb.pdf', 1, 'Nonaktif manual oleh Admin', '2026-08-28 10:23:53', '2026-08-27 08:14:33', '2026-08-31', '2026-12-31', '2026-08-27 01:14:33', '2026-08-28 03:23:53'),
(22, 'MAGANG-2026-0013', 21, 3, 'registrations/202608/cv_202608_user21_20260828110525_059427e2.pdf', 'registrations/202608/surat_pengantar_202608_user21_20260828110525_ba655de9.pdf', 'registrations/202608/proposal_magang_202608_user21_20260828110525_ff45fd41.pdf', 'rejected', 'selamat, tapi boong', NULL, 0, NULL, NULL, '2026-08-28 11:05:25', '2026-08-29', '2026-12-29', '2026-08-28 04:05:25', '2026-09-03 05:54:18');

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
('6JK5dzUZDBEI7Sh7KTFiX27Jx0ABuBWmcmGUCIjG', 22, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJjS0tKRnBWNnRYZW5CeWNQSkdGdloxMGVKdFlnUDFIQk9wM1hHWWQ1IiwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjIyLCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDAiLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1788489502),
('bLuevdkcNytCYztAjAhQdNEY3cZh5M8dLAkc4GlP', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJPNElSTXNMOGxrUDl2M3dLOXpMeHZHcHd6VlVaVWpWdVlpU3ZvOWpXIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1788486005),
('lcC9bQ7nRtxTGveJ9CoS7f1pmKVEDIl4LnsJOoQR', 22, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJWcUY3Qk5iUlZScXpNWUtuOVhoamlQRnhXYnFKallUZ24wT2hMdHZCIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvYWN0aXZlLWludGVybnMiLCJyb3V0ZSI6ImFkbWluLmFjdGl2ZS1pbnRlcm5zLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjIyfQ==', 1788491983),
('P4GvNV0XpVsHE7TZjIfm91D6YumkjIMPaME9ADev', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', 'eyJfdG9rZW4iOiI5U002TmJKeFRkcXR0TDBmZ0J5ZHdyRngzTzlVQ2h4WTRGRjcxbWd6IiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC9wb3NpdGlvbnMifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1788488711),
('r9cYmb92V5DV0mEt2TmEV4gXXPh6IIjIIycCShWX', 24, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJUc1dnUzNseXVyemdwemJ6ZHNia2tlMzdaeFF5NVROa0ljOXJYeGVUIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL3BhcnRpY2lwYW50XC9kYXNoYm9hcmQiLCJyb3V0ZSI6InBhcnRpY2lwYW50LmRhc2hib2FyZCJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MjR9', 1788505806);

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
(6, 5, 'oke', '127.0.0.1', '2026-09-03 07:34:06', '2026-09-03 07:34:06');

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
(21, 'bagus dwi', 'bagusdwijunior@gmail.com', NULL, NULL, 'peserta', NULL, '$2y$12$lAd8Ha/xKmKZ42V915pd/eRg18MaiRH/Ipm5GNbZDc8Ouq5874c..', '5C3bIHhLcw5O9nooOX7llgIZ1qNVkyp6RRAoYT231eqW9gyv7B1Srdvw7eSA', '2026-08-05 22:23:05', '2026-08-26 06:10:19'),
(22, 'Administrator Diskominfo Tuban', 'diskominfo@tubankab.go.id', '19820315 200801 1 004', 'Kepala Bidang Aplikasi & Informatika Diskominfo Tuban', 'admin', NULL, '$2y$12$VuWRgWkaonclWYYYfy1bF.4ORlQepRege6MVtM4YmLavYar/5umL6', 'To9IwoRTtP2c3EqnIfIFT4sx8I2UcLYTCatcxz6y0h4JuVZNOcfGrDyJ5sbG', '2026-08-05 22:28:35', '2026-09-04 06:59:23'),
(23, 'putra', 'bagusputra122334@gmail.com', NULL, NULL, 'peserta', NULL, '$2y$12$SkIZrJqrq96U6NwFaP6I6uBCvXuuww9/FHbc2rDB9drvbrmySSZwi', NULL, '2026-08-06 00:00:03', '2026-08-12 20:00:49'),
(24, 'bagus nih', '24050974004@mhs.unesa.ac.id', NULL, NULL, 'peserta', NULL, '$2y$12$cEz9StkJ.m70HDaHlajOaOx4gquXSW3I9jtCR49UqQq/Dg6.LDgNS', 'WDpqWaVfovLj1aOMcOygyjdktqGoaPBI3OxelvSsgndjCDUV3ihlKr3Afep1', '2026-08-09 19:45:54', '2026-08-13 20:29:55'),
(25, 'Atlantis', 'atlantispti@gmail.com', NULL, NULL, 'peserta', NULL, '$2y$12$xfjklyVtGhBGFScNk8dhYOBDmf2mwUA5AqJPCZnL8yBq/MeiujVhG', NULL, '2026-08-09 23:30:10', '2026-08-12 20:00:50'),
(33, 'DPM FT UNESA', 'dpmft@unesa.ac.id', NULL, NULL, 'peserta', NULL, '$2y$12$..tXu4buyWh5xTUhIpD8POq06GRdqpnn1Vz6LMHFzuFj.bYovLFxC', '8L2C9kUg36nA9JcOhgacFvgESSdPUKlzs2eAbov7xwTcDTs1tmjMxnsf6VBz', '2026-08-11 01:03:15', '2026-08-24 07:09:28'),
(34, 'Titin Yuni Riyanti M.Pd', 'mmandasari@example.com', NULL, NULL, 'peserta', '2026-08-24 07:19:56', '$2y$12$utHpTvFzucmijrmOTiuoPOLMEyoiOXsma7hsUWzsdk9/yfrWBcgc2', 'V8TE2toArC', '2026-08-24 07:19:56', '2026-08-24 07:19:56'),
(35, 'UNAISAH DZATTU IKHTISAMAH', '24050974097@mhs.unesa.ac.id', NULL, NULL, 'peserta', NULL, '$2y$12$j1U77pvN0gwbeRVPmrjXIepcO7SI4CAKoS/JgR4OpFOgPu9cN6xBG', NULL, '2026-08-24 07:59:01', '2026-08-24 07:59:01');

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `surveys`
--
ALTER TABLE `surveys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

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
