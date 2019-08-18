<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace GunWiki\SiteInfo\Cache;

class JsonCache implements Cache
{
    private $filename;

    public function __construct(string $filename)
    {
        if (!file_exists($filename)) {
            $this->writeJsonToFile($filename, []);
        }
        $this->filename = $filename;
    }

    public function get(string $key) :? string
    {
        $json = $this->readFileAsJson($this->filename);
        if (isset($json[$key])) {
            return $json[$key];
        }
        return null;
    }

    public function set(string $key, string $value)
    {
        $json = $this->readFileAsJson($this->filename);
        $json[$key] = $value;
        $this->writeJsonToFile($this->filename, $json);
    }

    public function unset(string $key) : void
    {
        $json = $this->readFileAsJson($this->filename);
        unset($json[$key]);
        $this->writeJsonToFile($this->filename, $json);
    }

    public function has(string $key): bool
    {
        $json = $this->readFileAsJson($this->filename);
        return isset($json[$key]);
    }

    public function cleanAll(): void
    {
        $this->writeJsonToFile($this->filename, []);
    }

    private function readFileAsJson(string $filename) : array
    {
        return json_decode(file_get_contents($filename), true);
    }

    private function writeJsonToFile(string $filename, array $json) : void
    {
        file_put_contents($filename, json_encode($json));
    }
}
