<?php

declare(strict_types=1);

/*
 * This file is part of Bootstrap Responsive YouTube Embed.
 *
 * (c) Marko Cupic 2024 <m.cupic@gmx.ch>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/bootstrap_responsive_youtube_embed
 */

namespace Markocupic\BootstrapResponsiveYoutubeEmbed\Migration\Version210;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @internal
 */
class UpdateAspectRatios extends AbstractMigration
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getName(): string
    {
        return 'Bootstrap Responsive Youtube Embed 2.1.0 Update: Update tl_content.playerAspectRatio';
    }

    /**
     * @throws Exception
     */
    public function shouldRun(): bool
    {
        $schemaManager = $this->connection->createSchemaManager();

        if (!$schemaManager->tablesExist(['tl_content'])) {
            return false;
        }

        $columns = $schemaManager->listTableColumns('tl_content');

        if (!isset($columns['type']) || !isset($columns['playeraspectratio'])) {
            return false;
        }

        $aspectRatios = array_keys($this->getAspectRatios());

        $result = $this->connection->fetchOne(
            'SELECT id FROM tl_content WHERE type = ? AND (playerAspectRatio = ? || playerAspectRatio = ? || playerAspectRatio = ? || playerAspectRatio = ? || playerAspectRatio = ? || playerAspectRatio = ? || playerAspectRatio = ? || playerAspectRatio = ?)',
            [
                'bootstrapYoutubeResponsiveEmbed',
                ...$aspectRatios,
            ]
        );

        return false !== $result;
    }

    /**
     * @throws Exception
     */
    public function run(): MigrationResult
    {
        $aspectRatios = $this->getAspectRatios();

        foreach ($aspectRatios as $oldAspectRatio => $newAspectRatio) {
            $set = [
                'playerAspectRatio' => $newAspectRatio,
            ];
            $this->connection->update('tl_content', $set, ['type' => 'bootstrapYoutubeResponsiveEmbed', 'playerAspectRatio' => $oldAspectRatio]);
        }

        return $this->createResult(true);
    }

    #[ArrayShape(['embed-responsive-21by9' => 'string', 'embed-responsive-16by9' => 'string', 'embed-responsive-4by3' => 'string', 'embed-responsive-1by1' => 'string', '21x9' => 'string', '16x9' => 'string', '4x3' => 'string', '1x1' => 'string'])]
    private function getAspectRatios(): array
    {
        return [
            'embed-responsive-21by9' => '21x9',
            'embed-responsive-16by9' => '16x9',
            'embed-responsive-4by3' => '4x3',
            'embed-responsive-1by1' => '1x1',
            '21by9' => '21x9',
            '16by9' => '16x9',
            '4by3' => '4x3',
            '1by1' => '1x1',
        ];
    }
}
