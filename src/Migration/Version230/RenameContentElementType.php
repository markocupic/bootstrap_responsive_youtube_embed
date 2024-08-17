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

namespace Markocupic\BootstrapResponsiveYoutubeEmbed\Migration\Version230;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Types;

/**
 * @internal
 */
class RenameContentElementType extends AbstractMigration
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getName(): string
    {
        return 'Bootstrap Responsive Youtube Embed 2.3.0 Update: Rename content element type from "bootstrapYoutubeResponsiveEmbed" to "bootstrap_youtube_responsive_embed"';
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

        if (!isset($columns['type'])) {
            return false;
        }

        $result = $this->connection->fetchOne(
            'SELECT id FROM tl_content WHERE type = ?',
            [
                'bootstrapYoutubeResponsiveEmbed',
            ],
            [
                Types::STRING,
            ]
        );

        return false !== $result;
    }

    /**
     * @throws Exception
     */
    public function run(): MigrationResult
    {
        $set = [
            'type' => 'bootstrap_youtube_responsive_embed',
        ];

        $this->connection->update('tl_content', $set, ['type' => 'bootstrapYoutubeResponsiveEmbed'], ['type' => Types::STRING]);

        return $this->createResult(true);
    }
}
