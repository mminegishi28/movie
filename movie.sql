-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2024-06-13 10:14:19
-- サーバのバージョン： 10.4.32-MariaDB
-- PHP のバージョン: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `movie`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `flag` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `login`
--

INSERT INTO `login` (`id`, `name`, `email`, `nickname`, `flag`) VALUES
(2, '峯岸', 'aa@gmail.com', 'maho', 0),
(3, 'admin', 'zzz@gmail.com', 'mm', 0),
(4, '田中', 'ss@gmail.com', 'mmm', 0),
(5, 'admin22', 'zzz@gmail.com', 'mmm', 0);

-- --------------------------------------------------------

--
-- テーブルの構造 `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `opening` int(255) NOT NULL,
  `director` varchar(255) NOT NULL,
  `summary` varchar(800) NOT NULL,
  `image_data` varchar(255) NOT NULL,
  `flag` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `movies`
--

INSERT INTO `movies` (`id`, `title`, `opening`, `director`, `summary`, `image_data`, `flag`) VALUES
(1, 'レオン', 19950325, 'リュック・ベッソン\r\n\r\n', '外出中に家族を惨殺された12歳の少女・マチルダは、隣に住んでいる男・レオンに助けを求める。レオンが凄腕の殺し屋であることを知り、そして彼の言葉に共感を覚えたマチルダは、殺しの技術を教えてほしいと願い出る。そして奇妙な同居生活を始めた2人は、次第に心を通わせていく。', 'images\\leon.jpeg', 0),
(2, '君の名前で僕を呼んで', 0, '', '', 'images\\kimi.jpeg', 0),
(3, 'ブラックスワン', 0, '', '', 'images\\black.jpeg', 0),
(4, 'コララインとボタンの魔女', 0, '', '', 'images\\coraline.jpeg', 0),
(5, 'グランドイリュージョン', 0, '', '4人組のスーパー・イリュージョニスト集団が、ラスベガスでのショーの最中にパリの銀行から大金を盗む。合同捜査を開始したFBIとインターポールは、次なる犯行を未然に防ぐために彼らを拘束し、トリックを解明しようと奮闘する。', 'images\\grand.jpeg', 0),
(6, 'ラ・ラ・ランド', 0, '', '', 'images\\lalaland.jpeg', 0),
(7, 'シャーロックホームズ', 0, '', '', 'images\\sherlock.jpeg', 0),
(8, 'ビフォアサンライズ', 0, '', '', 'images\\sunrise.jpeg', 0),
(9, 'トワイライト', 0, '', '', 'images\\twilight.jpeg', 0),
(10, '世界一キライなあなたに', 0, '', '', 'images\\world.jpeg', 0),
(11, 'ワイルドスピード', 2001, 'ロブ・コーエン', '', 'images/wild1.jpeg', 0),
(12, 'ワイルドスピード-スーパーコンボ-', 2019, 'デヴィッド・リーチ', '', 'images/wild2.jpeg', 0),
(13, 'オリエント急行殺人事件', 2017, 'ケネス・ブラナー', '', 'images/orient.jpeg', 0),
(34, 'ウォンカとチョコレート工場のはじまり', 2023, 'ポール・キング', '', 'images/wonka.jpeg', 0),
(42, 'MEGザモンスター', 2018, 'ジョン・タートルトーブ', '大陸から200kmの沖合に浮かぶ海洋研究施設。海底調査をしていた探査船が、ある海溝で消息を絶つ。潜水レスキューの男は、救助先で絶滅したはずの巨大ザメ・メガロドン (MEG)に遭遇。そして施設を破壊しビーチに近づいていくMEGを、彼らは必死に追う。', 'images/meg.jpeg', 0),
(52, 'Ms&Mrsスミス', 2005, 'ダぐ', '', 'images/', 0),
(81, 'オットーという男', 2023, 'マーク・フォースター', '妻を亡くして以来、不幸な日々を送るオットー。しかし、近くに引っ越してきた若い家族と出会い、機転の利くマリソルとの友情が、彼の人生を大きく変えることになる。', 'images/otto.jpeg', 0);

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- テーブルの AUTO_INCREMENT `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
