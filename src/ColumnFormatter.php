<?php

namespace LTDBeget\Rush;


class ColumnFormatter
{

    const PADDING = 2;

    /**
     * @param array $data
     * @param int $height
     * @param int $widthRow
     *
     * @return string
     */
    public function format(array $data, int $height, int $widthRow) : string
    {
        $width = $this->calculateWidthColumn($data);
        $actualHeight = $this->calculateHeightColumn(count($data), $height, $width, $widthRow);
        $data = $this->prepare($data, $actualHeight);

        return $this->getOutput($data, $width, $actualHeight);
    }

    /**
     * @param int $qty
     * @param int $heightCol
     * @param int $widthCol
     * @param int $widthRow
     *
     * @return int
     */
    protected function calculateHeightColumn(int $qty, int $heightCol, int $widthCol, int $widthRow) : int
    {
        // try to perform with config height
        $count = (int)ceil($qty / $heightCol);
        $availableWidth = (int)(ceil(($widthRow - self::PADDING) / $count) - self::PADDING);
        $isCanBePlaced = $widthCol <= $availableWidth;

        if ($isCanBePlaced) {
            return $heightCol;
        } else {
            // otherwise using auto calculation
            $count = (int)floor(($widthRow - self::PADDING) / $widthCol);

            return ceil($qty / $count);
        }
    }

    /**
     * @param array $data
     *
     * @return int
     */
    protected function calculateWidthColumn(array $data) : int
    {
        $max = 0;

        foreach ($data as $str) {
            $l = mb_strlen($str);

            if ($l > $max) {
                $max = $l;
            }
        }

        return $max + self::PADDING;
    }

    /**
     * @param array $data
     * @param int $width
     * @param int $height
     * @return string
     */
    protected function getOutput(array $data, int $width, int $height) : string
    {
        $rowChunk = floor(count($data) / $height);
        $output = PHP_EOL;

        foreach ($data as $i => $column) {
            $output .= $this->normalize($column, $width);

            $isRowChunk = (($i + 1) % $rowChunk) === 0;

            if ($isRowChunk) {
                $output .= PHP_EOL;
            }
        }

        return $output;
    }

    /**
     * @param array $data
     * @param int $size
     * @return array
     */
    protected function prepare(array $data, int $size) : array
    {
        $result = [];
        $chunked = array_chunk($data, $size);
        $i = 0;

        next:
        foreach ($chunked as $chunk) {
            $result[] = $chunk[$i] ?? '';
        }

        if (isset($chunked[0][++$i])) {
            goto next;
        }

        return $result;
    }

    /**
     * @param string $str
     * @param int $colWidth
     * @return string
     */
    protected function normalize(string $str, int $colWidth) : string
    {
        $void = str_repeat(' ', $colWidth - mb_strlen($str));

        return $str . $void;
    }

}