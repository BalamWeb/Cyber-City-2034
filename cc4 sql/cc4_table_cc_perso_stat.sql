
-- --------------------------------------------------------

--
-- Structure de la table `cc_perso_stat`
--

DROP TABLE IF EXISTS `cc_perso_stat`;
CREATE TABLE `cc_perso_stat` (
  `persoid` int(5) UNSIGNED NOT NULL,
  `statid` smallint(2) UNSIGNED NOT NULL,
  `xp` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cc_perso_stat`
--

INSERT INTO `cc_perso_stat` (`persoid`, `statid`, `xp`) VALUES
(481, 1, 2),
(481, 2, -2),
(481, 3, 4),
(481, 4, -152),
(481, 5, 148),
(889, 1, 277),
(889, 2, -424),
(889, 3, -122),
(889, 4, 10),
(889, 5, 259),
(1110, 1, 25),
(1110, 2, -81),
(1110, 3, 65),
(1110, 4, -32),
(1110, 5, 23),
(1423, 1, -7),
(1423, 2, -13),
(1423, 3, 29),
(1423, 4, -10),
(1423, 5, 1),
(1480, 1, -32),
(1480, 2, 56),
(1480, 3, -59),
(1480, 4, 29),
(1480, 5, 6),
(1534, 1, -33),
(1534, 2, 99),
(1534, 3, -240),
(1534, 4, 162),
(1534, 5, 12),
(1535, 1, 63),
(1535, 2, 9),
(1535, 3, -4),
(1535, 4, -87),
(1535, 5, 19),
(1585, 1, -22),
(1585, 2, 2),
(1585, 3, 33),
(1585, 4, 29),
(1585, 5, -42),
(1607, 1, 22),
(1607, 2, -131),
(1607, 3, -52),
(1607, 4, 88),
(1607, 5, 73),
(1627, 1, 40),
(1627, 2, -42),
(1627, 3, 21),
(1627, 4, 5),
(1627, 5, -24),
(1629, 1, 96),
(1629, 2, 113),
(1629, 3, -166),
(1629, 4, -99),
(1629, 5, 56),
(1631, 1, -25),
(1631, 2, 26),
(1631, 3, -36),
(1631, 4, -16),
(1631, 5, 51),
(1637, 1, 15),
(1637, 2, 45),
(1637, 3, -134),
(1637, 4, 27),
(1637, 5, 47),
(1643, 1, -15),
(1643, 2, 187),
(1643, 3, -274),
(1643, 4, 141),
(1643, 5, -39),
(1655, 1, -50),
(1655, 2, -24),
(1655, 3, -76),
(1655, 4, 74),
(1655, 5, 76),
(1669, 1, 96),
(1669, 2, -36),
(1669, 3, -79),
(1669, 4, -38),
(1669, 5, 57),
(1670, 1, 16),
(1670, 2, -53),
(1670, 3, -27),
(1670, 4, 52),
(1670, 5, 12),
(1674, 1, 0),
(1674, 2, 0),
(1674, 3, 0),
(1674, 4, 0),
(1674, 5, 0),
(1675, 1, 0),
(1675, 2, 0),
(1675, 3, 0),
(1675, 4, 0),
(1675, 5, 0),
(1676, 1, 1),
(1676, 2, -2),
(1676, 3, 0),
(1676, 4, 0),
(1676, 5, 1),
(1677, 1, 0),
(1677, 2, 0),
(1677, 3, 0),
(1677, 4, 0),
(1677, 5, 0),
(1678, 1, 0),
(1678, 2, 0),
(1678, 3, 0),
(1678, 4, 0),
(1678, 5, 0),
(1679, 1, 0),
(1679, 2, 0),
(1679, 3, 0),
(1679, 4, 0),
(1679, 5, 0),
(1681, 1, 79),
(1681, 2, 54),
(1681, 3, -144),
(1681, 4, -51),
(1681, 5, 62),
(1686, 1, -50),
(1686, 2, 169),
(1686, 3, -118),
(1686, 4, 85),
(1686, 5, -86),
(1698, 1, 3),
(1698, 2, 6),
(1698, 3, -12),
(1698, 4, -12),
(1698, 5, 15),
(1704, 1, -103),
(1704, 2, 180),
(1704, 3, -171),
(1704, 4, 121),
(1704, 5, -27),
(1714, 1, 30),
(1714, 2, 41),
(1714, 3, -26),
(1714, 4, -26),
(1714, 5, -19),
(1742, 1, 37),
(1742, 2, -70),
(1742, 3, 8),
(1742, 4, -4),
(1742, 5, 29),
(1743, 1, 5),
(1743, 2, -10),
(1743, 3, 0),
(1743, 4, 0),
(1743, 5, 5),
(1744, 1, -27),
(1744, 2, 55),
(1744, 3, -26),
(1744, 4, 27),
(1744, 5, -29),
(1745, 1, 28),
(1745, 2, -53),
(1745, 3, 6),
(1745, 4, -3),
(1745, 5, 22),
(1748, 1, 11),
(1748, 2, -17),
(1748, 3, 10),
(1748, 4, -5),
(1748, 5, 1),
(1749, 1, 10),
(1749, 2, -18),
(1749, 3, -1),
(1749, 4, -1),
(1749, 5, 10),
(1750, 1, 0),
(1750, 2, 0),
(1750, 3, 0),
(1750, 4, 0),
(1750, 5, 0),
(1751, 1, 0),
(1751, 2, 0),
(1751, 3, 0),
(1751, 4, 0),
(1751, 5, 0),
(1752, 1, 14),
(1752, 2, -28),
(1752, 3, 0),
(1752, 4, 0),
(1752, 5, 14),
(1756, 1, 9),
(1756, 2, -16),
(1756, 3, 1),
(1756, 4, -1),
(1756, 5, 7),
(1757, 1, 21),
(1757, 2, -37),
(1757, 3, 10),
(1757, 4, -5),
(1757, 5, 11),
(1761, 1, 12),
(1761, 2, -23),
(1761, 3, 2),
(1761, 4, -1),
(1761, 5, 10),
(1762, 1, 9),
(1762, 2, -18),
(1762, 3, 0),
(1762, 4, 0),
(1762, 5, 9),
(1763, 1, 0),
(1763, 2, 0),
(1763, 3, 0),
(1763, 4, 0),
(1763, 5, 0),
(1767, 1, 0),
(1767, 2, 0),
(1767, 3, 0),
(1767, 4, 0),
(1767, 5, 0),
(1768, 1, 21),
(1768, 2, -35),
(1768, 3, 4),
(1768, 4, -4),
(1768, 5, 14),
(1770, 1, 0),
(1770, 2, 0),
(1770, 3, 0),
(1770, 4, 0),
(1770, 5, 0),
(1773, 1, -50),
(1773, 2, 25),
(1773, 3, -51),
(1773, 4, 51),
(1773, 5, 25),
(1781, 1, 1),
(1781, 2, -2),
(1781, 3, 0),
(1781, 4, 0),
(1781, 5, 1),
(1782, 1, 0),
(1782, 2, 0),
(1782, 3, 0),
(1782, 4, 0),
(1782, 5, 0),
(1789, 1, 50),
(1789, 2, -50),
(1789, 3, -25),
(1789, 4, 0),
(1789, 5, 25),
(1791, 1, 11),
(1791, 2, -39),
(1791, 3, 38),
(1791, 4, 36),
(1791, 5, -46),
(1794, 1, 25),
(1794, 2, -19),
(1794, 3, -61),
(1794, 4, 24),
(1794, 5, 31),
(1795, 1, 0),
(1795, 2, 0),
(1795, 3, 0),
(1795, 4, 0),
(1795, 5, 0),
(1800, 1, 1),
(1800, 2, 0),
(1800, 3, 0),
(1800, 4, -1),
(1800, 5, 0),
(1803, 1, 0),
(1803, 2, 0),
(1803, 3, 0),
(1803, 4, 0),
(1803, 5, 0),
(1805, 1, 2),
(1805, 2, -5),
(1805, 3, -2),
(1805, 4, 3),
(1805, 5, 2),
(1808, 1, -25),
(1808, 2, -50),
(1808, 3, 0),
(1808, 4, 50),
(1808, 5, 25),
(1809, 1, 0),
(1809, 2, 0),
(1809, 3, -1),
(1809, 4, 1),
(1809, 5, 0),
(1817, 1, 0),
(1817, 2, -25),
(1817, 3, 75),
(1817, 4, -25),
(1817, 5, -25),
(1818, 1, 0),
(1818, 2, -25),
(1818, 3, 0),
(1818, 4, 0),
(1818, 5, 25),
(1819, 1, 0),
(1819, 2, 0),
(1819, 3, 0),
(1819, 4, 0),
(1819, 5, 0),
(1827, 1, 0),
(1827, 2, 0),
(1827, 3, 0),
(1827, 4, 0),
(1827, 5, 0),
(1831, 1, -49),
(1831, 2, 51),
(1831, 3, -9),
(1831, 4, -22),
(1831, 5, 29),
(1832, 1, 25),
(1832, 2, -25),
(1832, 3, 50),
(1832, 4, -75),
(1832, 5, 25),
(1837, 1, 25),
(1837, 2, 25),
(1837, 3, -50),
(1837, 4, -25),
(1837, 5, 25),
(1838, 1, 0),
(1838, 2, 0),
(1838, 3, 0),
(1838, 4, 0),
(1838, 5, 0),
(1839, 1, -17),
(1839, 2, 66),
(1839, 3, -115),
(1839, 4, 8),
(1839, 5, 58),
(1840, 1, 0),
(1840, 2, 0),
(1840, 3, 0),
(1840, 4, 0),
(1840, 5, 0),
(1842, 1, 60),
(1842, 2, -93),
(1842, 3, 49),
(1842, 4, -74),
(1842, 5, 58),
(1843, 1, 34),
(1843, 2, -6),
(1843, 3, -43),
(1843, 4, 3),
(1843, 5, 12),
(1845, 1, 0),
(1845, 2, 0),
(1845, 3, 0),
(1845, 4, 0),
(1845, 5, 0),
(1846, 1, 3),
(1846, 2, -5),
(1846, 3, 2),
(1846, 4, -1),
(1846, 5, 1),
(1847, 1, 0),
(1847, 2, 0),
(1847, 3, 0),
(1847, 4, 0),
(1847, 5, 0),
(1850, 1, 0),
(1850, 2, 0),
(1850, 3, 0),
(1850, 4, 0),
(1850, 5, 0),
(1856, 1, 25),
(1856, 2, -25),
(1856, 3, -50),
(1856, 4, 25),
(1856, 5, 25),
(1862, 1, 0),
(1862, 2, 1),
(1862, 3, -3),
(1862, 4, 1),
(1862, 5, 1),
(1864, 1, -50),
(1864, 2, 0),
(1864, 3, 50),
(1864, 4, 50),
(1864, 5, -50),
(1865, 1, 0),
(1865, 2, -25),
(1865, 3, -75),
(1865, 4, 75),
(1865, 5, 25),
(1866, 1, 0),
(1866, 2, 0),
(1866, 3, 0),
(1866, 4, 0),
(1866, 5, 0),
(1877, 1, -32),
(1877, 2, 153),
(1877, 3, -76),
(1877, 4, 52),
(1877, 5, -97),
(1878, 1, 64),
(1878, 2, 63),
(1878, 3, -113),
(1878, 4, -45),
(1878, 5, 31),
(1879, 1, 50),
(1879, 2, 0),
(1879, 3, -50),
(1879, 4, 25),
(1879, 5, -25),
(1881, 1, -47),
(1881, 2, -30),
(1881, 3, 25),
(1881, 4, 51),
(1881, 5, 1),
(1891, 1, 25),
(1891, 2, 0),
(1891, 3, 0),
(1891, 4, 0),
(1891, 5, -25),
(1897, 1, 0),
(1897, 2, 0),
(1897, 3, 0),
(1897, 4, 0),
(1897, 5, 0),
(1898, 1, 81),
(1898, 2, -6),
(1898, 3, -84),
(1898, 4, -1),
(1898, 5, 10),
(1900, 1, 3),
(1900, 2, -4),
(1900, 3, -78),
(1900, 4, 49),
(1900, 5, 30),
(1901, 1, 0),
(1901, 2, 0),
(1901, 3, -25),
(1901, 4, 25),
(1901, 5, 0),
(1902, 1, 5),
(1902, 2, -6),
(1902, 3, 1),
(1902, 4, -1),
(1902, 5, 1),
(1904, 1, 0),
(1904, 2, 0),
(1904, 3, 0),
(1904, 4, 0),
(1904, 5, 0),
(1905, 1, 6),
(1905, 2, -12),
(1905, 3, 0),
(1905, 4, 0),
(1905, 5, 6),
(1906, 1, 0),
(1906, 2, 0),
(1906, 3, 0),
(1906, 4, 0),
(1906, 5, 0),
(1907, 1, 1),
(1907, 2, -2),
(1907, 3, 0),
(1907, 4, 0),
(1907, 5, 1),
(1908, 1, 6),
(1908, 2, -12),
(1908, 3, 0),
(1908, 4, 0),
(1908, 5, 6),
(1909, 1, 0),
(1909, 2, 0),
(1909, 3, 0),
(1909, 4, 0),
(1909, 5, 0),
(1910, 1, 25),
(1910, 2, 0),
(1910, 3, -26),
(1910, 4, 51),
(1910, 5, -50),
(1912, 1, -2),
(1912, 2, 4),
(1912, 3, 72),
(1912, 4, -72),
(1912, 5, -2),
(1913, 1, 50),
(1913, 2, 1),
(1913, 3, -26),
(1913, 4, 24),
(1913, 5, -49),
(1917, 1, 25),
(1917, 2, 25),
(1917, 3, -25),
(1917, 4, 0),
(1917, 5, -25),
(1918, 1, 27),
(1918, 2, -4),
(1918, 3, 23),
(1918, 4, -23),
(1918, 5, -23),
(1919, 1, 0),
(1919, 2, 0),
(1919, 3, 0),
(1919, 4, 0),
(1919, 5, 0),
(1921, 1, 0),
(1921, 2, 0),
(1921, 3, -1),
(1921, 4, 1),
(1921, 5, 0),
(1922, 1, 0),
(1922, 2, 0),
(1922, 3, 0),
(1922, 4, 0),
(1922, 5, 0),
(1924, 1, 0),
(1924, 2, 0),
(1924, 3, 0),
(1924, 4, 0),
(1924, 5, 0),
(1926, 1, 25),
(1926, 2, -25),
(1926, 3, -75),
(1926, 4, 50),
(1926, 5, 25),
(1929, 1, 0),
(1929, 2, 1),
(1929, 3, -1),
(1929, 4, -1),
(1929, 5, 1),
(1930, 1, 28),
(1930, 2, 27),
(1930, 3, -41),
(1930, 4, -23),
(1930, 5, 9),
(1931, 1, 25),
(1931, 2, 0),
(1931, 3, -75),
(1931, 4, 25),
(1931, 5, 25),
(1932, 1, 5),
(1932, 2, -6),
(1932, 3, -3),
(1932, 4, -1),
(1932, 5, 5),
(1933, 1, -50),
(1933, 2, 25),
(1933, 3, -50),
(1933, 4, 50),
(1933, 5, 25),
(1934, 1, 0),
(1934, 2, -50),
(1934, 3, 25),
(1934, 4, 75),
(1934, 5, -50),
(1935, 1, 25),
(1935, 2, -25),
(1935, 3, 25),
(1935, 4, 25),
(1935, 5, -50),
(1936, 1, -50),
(1936, 2, -50),
(1936, 3, 75),
(1936, 4, 75),
(1936, 5, -50),
(1937, 1, 50),
(1937, 2, -50),
(1937, 3, 74),
(1937, 4, -74),
(1937, 5, 0),
(1938, 1, -75),
(1938, 2, -71),
(1938, 3, -4),
(1938, 4, 71),
(1938, 5, 79),
(1939, 1, 0),
(1939, 2, -1),
(1939, 3, -1),
(1939, 4, 2),
(1939, 5, 0),
(1940, 1, 25),
(1940, 2, 25),
(1940, 3, -50),
(1940, 4, 25),
(1940, 5, -25),
(1941, 1, 75),
(1941, 2, -50),
(1941, 3, 50),
(1941, 4, -50),
(1941, 5, -25),
(1942, 1, 4),
(1942, 2, 1),
(1942, 3, -30),
(1942, 4, -5),
(1942, 5, 30),
(1943, 1, -25),
(1943, 2, 25),
(1943, 3, 0),
(1943, 4, 50),
(1943, 5, -50),
(1944, 1, -25),
(1944, 2, 50),
(1944, 3, -75),
(1944, 4, 50),
(1944, 5, 0),
(1945, 1, 48),
(1945, 2, 54),
(1945, 3, -2),
(1945, 4, -48),
(1945, 5, -52),
(1946, 1, 25),
(1946, 2, -73),
(1946, 3, 23),
(1946, 4, -27),
(1946, 5, 52),
(1947, 1, 29),
(1947, 2, -52),
(1947, 3, -28),
(1947, 4, 24),
(1947, 5, 27),
(1948, 1, 0),
(1948, 2, 25),
(1948, 3, 50),
(1948, 4, 0),
(1948, 5, -75),
(1949, 1, 0),
(1949, 2, 25),
(1949, 3, -51),
(1949, 4, 26),
(1949, 5, 0),
(1951, 1, -50),
(1951, 2, 0),
(1951, 3, 24),
(1951, 4, 76),
(1951, 5, -50),
(1952, 1, -25),
(1952, 2, -25),
(1952, 3, 0),
(1952, 4, 25),
(1952, 5, 25),
(1953, 1, 25),
(1953, 2, 75),
(1953, 3, -75),
(1953, 4, -50),
(1953, 5, 25),
(1954, 1, 75),
(1954, 2, -50),
(1954, 3, 25),
(1954, 4, 0),
(1954, 5, -50),
(1955, 1, -25),
(1955, 2, -20),
(1955, 3, 70),
(1955, 4, -5),
(1955, 5, -20),
(1956, 1, 25),
(1956, 2, 50),
(1956, 3, -75),
(1956, 4, 50),
(1956, 5, -50),
(1957, 1, 25),
(1957, 2, 0),
(1957, 3, 25),
(1957, 4, 0),
(1957, 5, -50),
(1959, 1, 0),
(1959, 2, 25),
(1959, 3, -25),
(1959, 4, 25),
(1959, 5, -25),
(1960, 1, 75),
(1960, 2, 25),
(1960, 3, -75),
(1960, 4, 50),
(1960, 5, -75);
